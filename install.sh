#!/usr/bin/env bash
set -e

# ============================================================
#  install.sh — Auto Installer Aplikasi Lokal EMIS
#  Dual Mode: Release (dari GitHub Actions) / Dev (clone repo)
# ============================================================

BOLD='\033[1m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

info()  { echo -e "${GREEN}[INFO]${NC} $1"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
error() { echo -e "${RED}[ERROR]${NC} $1"; }
step()  { echo -e "\n${BOLD}━━━ $1 ━━━${NC}"; }

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$PROJECT_DIR"

info "Project dir: $PROJECT_DIR"
info "OS: $(uname -s)"

# ── Deteksi Mode ─────────────────────────────────────────────
if [ -f vendor/autoload.php ] && [ -f public/build/manifest.json ]; then
  MODE="release"
  info "Mode: ${GREEN}RELEASE${NC} (dari GitHub Actions — build sudah include)"
elif [ -f vendor/autoload.php ]; then
  MODE="release"
  info "Mode: ${GREEN}RELEASE${NC} (vendor & public/build sudah ada)"
else
  MODE="dev"
  info "Mode: ${YELLOW}DEV${NC} (clone repo — perlu install dependencies)"
fi

# ── 1. Cek Requirements ──────────────────────────────────────
step "1. Memeriksa Requirements"

command -v php >/dev/null 2>&1 || { error "PHP tidak ditemukan. Install PHP 8.2+ dulu."; exit 1; }
PHP_VER=$(php -v | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
info "PHP $PHP_VER ✓"

command -v composer >/dev/null 2>&1 || { error "Composer tidak ditemukan."; exit 1; }
info "Composer ✓"

if [ "$MODE" = "dev" ]; then
  command -v node >/dev/null 2>&1 || { error "Node.js tidak ditemukan."; exit 1; }
  info "Node.js $(node -v) ✓"

  if command -v yarn >/dev/null 2>&1; then
    PKG_MGR="yarn"
    info "Yarn ✓"
  elif command -v npm >/dev/null 2>&1; then
    PKG_MGR="npm"
    warn "Yarn tidak ditemukan, pakai npm sebagai fallback."
  else
    error "Yarn atau npm tidak ditemukan."
    exit 1
  fi
else
  info "Node.js — skip (build sudah include di release)"
fi

PHP_EXT_NEEDED=(pdo pdo_mysql mbstring xml curl bcmath json fileinfo sodium gd zip dom)
for ext in "${PHP_EXT_NEEDED[@]}"; do
  php -m | grep -qi "^$ext$" || { warn "Ekstensi PHP '$ext' tidak terdeteksi."; }
done
# ── Cek & Restore composer.json jika Hilang ──────────────────
if [ ! -f composer.json ]; then
  warn "composer.json tidak ditemukan! Membuat composer.json minimal untuk autoloading..."
  cat > composer.json << 'EOF'
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Database\\Factories\\": "database/factories/",
      "Database\\Seeders\\": "database/seeders/"
    }
  }
}
EOF
  info "composer.json minimal berhasil dibuat."
fi

# ── 2. Setup .env ────────────────────────────────────────────
step "2. Konfigurasi .env"

if [ ! -f .env ]; then
  [ -f .env.example ] || { error ".env.example tidak ditemukan!"; exit 1; }
  cp .env.example .env
  info ".env dibuat dari .env.example"
else
  warn ".env sudah ada, dilewati."
fi

sed -i "s/^APP_NAME=.*/APP_NAME=\"Mansaba Official Website\"/" .env

echo ""
echo -e "${BOLD}Domain / URL Aplikasi${NC}"
echo "  Contoh: http://localhost:8000 / https://emis.madrasah.sch.id"
read -p "APP_URL [http://localhost:8000]: " APP_URL_INPUT
APP_URL="${APP_URL_INPUT:-http://localhost:8000}"
sed -i "s|^APP_URL=.*|APP_URL=$APP_URL|" .env
info "APP_URL → $APP_URL"

echo ""
read -p "DB Host [103.197.191.226]: " DB_HOST_INPUT
DB_HOST="${DB_HOST_INPUT:-103.197.191.226}"
read -p "DB Port [3306]: " DB_PORT_INPUT
DB_PORT="${DB_PORT_INPUT:-3306}"
read -p "DB Name [official_website]: " DB_NAME_INPUT
DB_NAME="${DB_NAME_INPUT:-official_website}"
read -p "DB Username [official_website]: " DB_USER_INPUT
DB_USER="${DB_USER_INPUT:-official_website}"
read -sp "DB Password: " DB_PASS_INPUT
echo ""
DB_PASS="${DB_PASS_INPUT}"

sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
for key in DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
  sed -i "s/^# ${key}=.*//" .env
done
sed -i "s/^DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
sed -i "s/^DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env

info "Database → mysql://${DB_USER}@${DB_HOST}:${DB_PORT}/${DB_NAME}"

# ── Fallback FILESYSTEM_DISK ────────────────────────────
if grep -q "^FILESYSTEM_DISK=s3$" .env 2>/dev/null; then
  if [ -z "$AWS_ACCESS_KEY_ID" ] && [ -z "$AWS_SECRET_ACCESS_KEY" ]; then
    sed -i "s/^FILESYSTEM_DISK=.*/FILESYSTEM_DISK=local/" .env
    warn "AWS S3 tidak dikonfigurasi, FILESYSTEM_DISK fallback ke 'local'"
  fi
fi

# ── 3. Install Dependencies PHP ─────────────────────────────
if [ "$MODE" = "dev" ]; then
  step "3. Install Dependencies PHP"
  if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist
  else
    composer update --no-interaction --prefer-dist
  fi
  info "Composer selesai"
else
  info "Step 3 — skip (vendor sudah include di release)"
fi

# ── 4. Publish Sanctum ───────────────────────────────────────
step "4. Publish Sanctum"
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force 2>/dev/null || true
info "Sanctum siap"

# ── 5. Optimize Autoloader ────────────────────────────────
step "5. Optimize Autoloader"
composer dump-autoload --optimize --no-interaction 2>/dev/null || true
info "Autoloader optimized"

# ── 6. Generate App Key ──────────────────────────────────────
step "6. Generate Application Key"

if grep -q "APP_KEY=$" .env 2>/dev/null; then
  php artisan key:generate --force
  info "APP_KEY generated"
else
  info "APP_KEY sudah ada"
fi

# ── 7. Storage Link ──────────────────────────────────────────
step "7. Storage Link"
php artisan storage:link --force 2>/dev/null || true
info "Storage link siap"

# ── 8. Frontend Dependencies ───────────────────────────────
if [ "$MODE" = "dev" ]; then
  step "8. Install Frontend Dependencies"
  if [ "$PKG_MGR" = "yarn" ]; then
    [ ! -d node_modules ] && yarn install --frozen-lockfile || warn "node_modules sudah ada"
  else
    [ ! -d node_modules ] && npm install || warn "node_modules sudah ada"
  fi
else
  info "Step 8 — skip (node_modules tidak perlu di release)"
fi

# ── 9. Build Frontend ──────────────────────────────────────
if [ "$MODE" = "dev" ]; then
  step "9. Build Frontend Assets"
  if [ "$PKG_MGR" = "yarn" ]; then
    yarn build
  else
    npm run build
  fi
  info "Frontend build selesai"
else
  info "Step 9 — skip (frontend sudah di-build di GitHub Actions)"
fi

# ── 10. Migrate Database ───────────────────────────────────
step "10. Migrate Database"

echo ""
echo "Pilih aksi database:"
echo "  1) migrate       — jalankan migration (tabel baru)"
echo "  2) migrate:fresh — hapus semua + migrasi ulang + seed"
echo "  3) lewati"
read -p "Pilihan [2]: " DB_ACTION
DB_ACTION="${DB_ACTION:-2}"

case $DB_ACTION in
  1) php artisan migrate --force; info "Migrasi selesai" ;;
  2)
    warn "SEMUA DATA DI DATABASE $DB_NAME AKAN DIHAPUS!"
    read -p "Ketik 'fresh' untuk lanjut: " CONFIRM
    if [ "$CONFIRM" = "fresh" ]; then
      php artisan migrate:fresh --force --seed
      info "Migrate:fresh + seed selesai"
    else
      warn "Dibatalkan."
    fi
    ;;
  3) warn "Migration dilewati." ;;
esac

# ── 11. Seed Super Admin Default ─────────────────────────────
step "11. Seed Super Admin Default"

php artisan tinker --execute="
if (\App\Models\User::where('email', 'admin@emis.local')->exists()) {
  echo 'Super Admin sudah ada, skip.\n';
  exit;
}

\$user = \App\Models\User::create([
  'name' => 'Super Admin',
  'email' => 'admin@emis.local',
  'password' => bcrypt('password'),
]);
\$user->assignRole('Super Admin');
echo 'Super Admin: admin@emis.local / password\n';
" 2>/dev/null || warn "Seeder user gagal (abaikan jika sudah ada data)"

# ── 12. Optimize ────────────────────────────────────────────
step "12. Optimize"
php artisan optimize:clear 2>/dev/null || true
info "Cache cleared"

# ── 13. Selesai ─────────────────────────────────────────────
step "13. Selesai!"

echo ""
echo -e "  ${GREEN}Mansaba Official Website siap digunakan!${NC}"
echo ""
echo "  Buka aplikasi: ${BOLD}$APP_URL${NC}"
echo ""
echo "  Role: Super Admin, Dinas, Operator, Guru, Kepala Sekolah, Siswa, Orang Tua"
echo ""
echo "  Login Super Admin:"
echo "    Email:    admin@emis.local"
echo "    Password: password"
echo ""
if [ "$MODE" = "dev" ]; then
  echo "  Development hot-reload: yarn dev"
fi
echo "  Reset data:             bash reset.sh"
echo ""

exit 0
