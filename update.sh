#!/usr/bin/env bash
set -e

# ============================================================
#  update.sh — Update Aplikasi Lokal EMIS dari GitHub
#  Jalankan di live site setiap ada perubahan dari repo
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

if [ ! -f artisan ]; then
    error "Tidak dapat menemukan artisan. Jalankan dari root project Laravel."
    exit 1
fi

# Deteksi kepemilikan yang benar
# Metode 1 (PRIMARY): Dari direktori project via stat (Linux)
ORIGINAL_OWNER=$(stat -c '%U:%G' "$PROJECT_DIR" 2>/dev/null)
DETECT_METHOD="stat -c"
if [ -z "$ORIGINAL_OWNER" ]; then
    # Metode 1b (PRIMARY fallback): Dari direktori project via ls (macOS)
    ORIGINAL_OWNER=$(ls -ld "$PROJECT_DIR" | awk '{print $3":"$4}' 2>/dev/null)
    DETECT_METHOD="ls -ld"
fi
if [ -z "$ORIGINAL_OWNER" ]; then
    # Metode 2 (SECONDARY): Deteksi dari proses PHP-FPM
    SERVER_USER=$(ps aux | grep -E "php-fpm|php-fpm[0-9]" | grep -v grep | awk 'NR==1{print $1}')
    if [ -z "$SERVER_USER" ]; then
        SERVER_USER="www-data"
    fi
    if ! id "$SERVER_USER" &>/dev/null; then
        SERVER_USER=$(ls -ld artisan | awk '{print $3}')
    fi
    ORIGINAL_OWNER="${SERVER_USER}:${SERVER_USER}"
    DETECT_METHOD="php-fpm"
fi
info "Project dir: $PROJECT_DIR"
info "Deteksi owner via: $DETECT_METHOD"
info "Target owner: $ORIGINAL_OWNER"

# ── 1. Git Pull ──────────────────────────────────────────────
step "1. Tarik perubahan dari GitHub"

CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "master")
REPO_URL="${GIT_REPO_URL:-}"

if [ ! -d .git ]; then
    if [ -n "$REPO_URL" ]; then
        info "Folder belum berupa git repo. Inisialisasi dan clone dari: $REPO_URL"
        git init
        git remote add origin "$REPO_URL"
        git fetch origin "$CURRENT_BRANCH" --depth=1
        git reset --hard "origin/$CURRENT_BRANCH"
        info "Clone selesai ✓"
    else
        warn "Folder bukan repository git dan GIT_REPO_URL tidak diset."
        warn "Lewati langkah git pull. Set variabel GIT_REPO_URL=<url> untuk mengaktifkan."
    fi
else
    git pull origin "$CURRENT_BRANCH"
    info "Kode terbaru dari GitHub ✓"
fi

# ── 2. Composer Install ─────────────────────────────────────
step "2. Update Dependencies PHP"

if command -v composer >/dev/null 2>&1; then
    composer install --no-dev --optimize-autoloader --no-interaction
    info "Composer dependencies siap ✓"
else
    warn "Composer tidak ditemukan, skip."
fi

# ── 2b. Livewire Assets ────────────────────────────────────
step "2b. Publish Livewire Assets"
php artisan livewire:publish --assets 2>/dev/null || php artisan vendor:publish --tag=livewire:assets --force 2>/dev/null || true
info "Livewire assets siap ✓"

# ── 3. Frontend Assets ──────────────────────────────────────
step "3. Frontend Assets (dari GitHub Release)"

REPO="${GIT_REPO_URL#*github.com/}"
REPO="${REPO#*github.com:}"
REPO="${REPO%%.git}"
REPO="${REPO:-lutfifuadi/official-mansaba}"
info "Download frontend assets dari release terbaru ($REPO)..."
curl -sL "https://github.com/$REPO/releases/latest/download/aplikasi.zip" \
    -o /tmp/emis-assets.zip 2>/dev/null || true

if [ -f /tmp/emis-assets.zip ]; then
    rm -rf "$PROJECT_DIR/public/build"
    unzip -o /tmp/emis-assets.zip "public/build/*" -d "$PROJECT_DIR" >/dev/null 2>&1 || true
    rm -f /tmp/emis-assets.zip
    if [ -f public/build/manifest.json ]; then
        info "Frontend assets dari release ✓"
    else
        warn "public/build tidak ditemukan dalam release. Jalankan 'npm run build' manual."
    fi
else
    warn "Gagal download release. Pastikan release 'latest-build' sudah ada."
fi

# ── 4. Migrate Database ─────────────────────────────────────
step "4. Migrasi Database"
php artisan migrate --force --graceful 2>&1 || true
info "Migrasi selesai ✓"

# ── 5. Optimasi ─────────────────────────────────────────────
step "5. Optimasi Cache"

# Hapus cache lama dulu agar Laravel bisa boot tanpa error (misal: Pail tidak ada di production)
rm -f bootstrap/cache/*.php 2>/dev/null || true
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
info "Optimasi selesai ✓"

# ── 6. Storage Link ─────────────────────────────────────────
step "6. Storage Link"
php artisan storage:link --force 2>/dev/null || true
info "Storage link siap ✓"

# ── 7. Permission & Ownership ──────────────────────────────
step "7. Set Permission & Ownership"
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R "$ORIGINAL_OWNER" "$PROJECT_DIR" 2>/dev/null || warn "Gagal restore ownership (butuh root/sudo)"
info "Permission & ownership OK ✓"

# ── Selesai ─────────────────────────────────────────────────
step "Selesai!"
echo ""
APP_NAME_DISPLAY=$(sed -n 's/^APP_NAME=//p' .env 2>/dev/null | head -1 | tr -d '"' || echo "Mansaba Official Website")
echo -e "  ${GREEN}${APP_NAME_DISPLAY} berhasil diupdate!${NC}"
echo ""
echo "  Perubahan yang dilakukan:"
echo "    - Code: git pull dari GitHub"
echo "    - PHP dependencies: composer install"
echo "    - Frontend: download dari GitHub Release"
echo "    - Database: migrate"
echo "    - Cache: dioptimasi ulang"
echo ""

exit 0
