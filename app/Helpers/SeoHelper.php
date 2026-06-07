<?php

namespace App\Helpers;

use App\Models\Setting;

class SeoHelper
{
    public static function organization(): array
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $settings['site_name'] ?? 'MAN 1 Kota Bandung',
            'url' => url('/'),
            'logo' => !empty($settings['school_logo'])
                ? (str_starts_with($settings['school_logo'], 'http')
                    ? $settings['school_logo']
                    : \Illuminate\Support\Facades\Storage::url($settings['school_logo']))
                : url('/assets/img/favicon/favicon.ico'),
            'description' => $settings['site_description'] ?? 'Website resmi MAN 1 Kota Bandung',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings['address'] ?? '',
                'telephone' => $settings['phone'] ?? '',
                'email' => $settings['email'] ?? '',
            ],
        ];
    }

    public static function website(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'MAN 1 Kota Bandung',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/berita?search={search_term_string}'),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public static function breadcrumbList(array $items): array
    {
        $itemListElement = [];
        $position = 1;

        foreach ($items as $item) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
                'item' => $item['url'] ?? url('/'),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement,
        ];
    }

    public static function newsArticle(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $data['title'],
            'image' => $data['image'] ?? '',
            'datePublished' => $data['published_at'] ?? '',
            'dateModified' => $data['updated_at'] ?? $data['published_at'] ?? '',
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'] ?? 'MAN 1 Kota Bandung',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'MAN 1 Kota Bandung',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url('/assets/img/favicon/favicon.ico'),
                ],
            ],
            'description' => $data['description'] ?? '',
        ];
    }
}
