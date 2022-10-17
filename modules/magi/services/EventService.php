<?php

namespace magi\services;

use craft\elements\Entry;
use magi\Magi;

class EventService
{
    public function beforeEntryDelete(Entry $entry)
    {
        Magi::getInstance()->request->send('DELETE', 'guilds/' . $entry->guild . '/scheduled-events/' . $entry->scheduledEventId);
    }

    public function beforeEntrySave(Entry $entry)
    {
        $params = [
            'body' => json_encode([
                'entity_type' => 2,
                'name' => $entry->title,
                'channel_id' => $entry->voiceChannel,
                'scheduled_start_time' => $entry->date->format(\DateTime::ATOM),
                'description' => strip_tags($entry->description),
                'privacy_level' => 2
            ])
        ];

        if ($entry->scheduledEventId) {
            $event = $this->update($entry, $params);
        } else {
            $event = $this->create($entry, $params);
        }

        $entry->scheduledEventId = $event->id;
    }

    private function create(Entry $entry, array $params)
    {
        return Magi::getInstance()->request->send('POST', 'guilds/' . $entry->guild . '/scheduled-events', $params);
    }

    private function update(Entry $entry, array $params)
    {
        return Magi::getInstance()->request->send('PATCH', 'guilds/' . $entry->guild . '/scheduled-events/' . $entry->scheduledEventId, $params);
    }
}