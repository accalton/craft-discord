<?php

return [
    'sync' => true,
    'queue' => false,
    'batch_size' => 1000,

    'application_id' => '$ALGOLIA_APPLICATION_ID',
    'admin_api_key'  => '$ALGOLIA_ADMIN_API_KEY',
    'search_api_key' => '$ALGOLIA_SEARCH_API_KEY',

    'indices' => [
        \rias\scout\ScoutIndex::create('Guilds')
            ->elementType(\craft\elements\Entry::class)
            ->criteria(function (\craft\elements\db\EntryQuery $query) {
                return $query->section('guilds');
            })
            ->transformer(function (\craft\elements\Entry $entry) {
                return [
                    'title' => $entry->title
                ];
            })
            ->indexSettings(
                \rias\scout\IndexSettings::create()
                    ->searchableAttributes([
                        'title'
                    ])
            ),
        \rias\Scout\ScoutIndex::create('Messages')
            ->elementType(\craft\elements\Entry::class)
            ->criteria(function (\craft\elements\db\EntryQuery $query) {
                return $query->section('messages');
            })
            ->transformer(function (\craft\elements\Entry $entry) {
                return [
                        'title' => $entry->title
                ];
            })
            ->indexSettings(
                \rias\scout\IndexSettings::create()
                    ->searchableAttributes([
                        'title'
                    ])
            )
    ]
];
