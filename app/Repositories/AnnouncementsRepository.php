<?php

namespace App\Repositories;

use App\Interfaces\AnnouncementsInterface;
use App\Models\Announcement;

class AnnouncementsRepository implements AnnouncementsInterface{

    /**
     * Get all lists from table announcements
     * 
     * @return object
     */
    public function getAnnouncements($per_page = 15)
    {
        $a = Announcement::where('announcement_status', 'Published')
            ->orderBy('published_at', 'desc')
            ->paginate($per_page);

        return $a;
    }

    /**
     * Get details announcement
     * 
     * @return object
     */
    public function getAnnouncement($id)
    {
        $a = Announcement::where('announcement_status', 'Published')->orderBy('published_at', 'desc')->get();

        return (object)$a;
    }
}