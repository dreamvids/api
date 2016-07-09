<?php
class HomeCtrl implements ControllerInterface {
    public static function create() {
        // TODO: Implement create() method.
    }

    public static function fetch() {
        Response::get()->addData('TITLE', 'Accueil');
        debug([
            'channel' => ChannelTable::get()->getAssociations(),
            'channelAdmin' => ChannelAdminTable::get()->getAssociations(),
            'comment' => CommentTable::get()->getAssociations(),
            'rank' => RankTable::get()->getAssociations(),
            'session' => SessionTable::get()->getAssociations(),
            'user' => UserTable::get()->getAssociations(),
            'video' => VideoTable::get()->getAssociations(),
            'visibility' => VisibilityTable::get()->getAssociations()
        ]);
    }

    public static function exists() {
        // TODO: Implement exists() method.
    }

    public static function read() {
        // TODO: Implement read() method.
    }

    public static function update() {
        // TODO: Implement update() method.
    }

    public static function delete() {
        // TODO: Implement delete() method.
    }
}