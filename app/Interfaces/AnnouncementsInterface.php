<?php
namespace App\Interfaces;

interface AnnouncementsInterface {
    public function getAnnouncements($q);
    public function getAnnouncement($id);
}