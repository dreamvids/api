<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 02/07/2016
 * Time: 12:52
 */

class VideoCtrl implements ControllerInterface {
    use ExistsTrait;

    public static function create() {
        $validation = new Validator([
            'title' => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'Title required'
                ]
            ],
            'video' => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'A video file is (obviously) required...'
                ]
            ]
        ], $_POST + $_FILES);

        if($validation->validate()) {
            $vidId = Utils::generateId();
            $basedir = System::get()->getRoot().'uploads/';

            $video_info = pathinfo($_FILES['video']['tmp_name']);
            $video_ext = $video_info['extension'];
            $video_path = $basedir.$vidId.'.'.$video_ext;

            if (isset($_FILES['thumbnail'])) {
                $thumbnail_info = pathinfo($_FILES['thumbnail']['tmp_name']);
                $thumbnail_ext = $thumbnail_info['extenstion'];
                $thumbnail_path = $basedir.$vidId.'.'.$thumbnail_ext;
                //TODO: Copier la miniature sur les serveurs de stockage puis en récup l'URL
                $thumbnail_path = 'http://.../'.$vidId.'.'.$thumbnail_ext;
            }
            else {
                $thumbnail_path = System::get()->getWebroot().'assets/img/defaultThumbnail.png';
            }

            if (move_uploaded_file($_FILES['video']['tmp_name'], $video_path)) {
                // TODO: Set video duration
                $video_duration = 0;
                // TODO: POST conversion.dreamvids.fr/
                $video_url = 'http://.../video/'.$vidId.'.'.$video_ext;
                // TODO: get channel_id & visibility_id
                $channel_id = 0;
                $visibility_id = 0;
                Video::insertIntoDb([$_POST['title'], $_POST['description'], $thumbnail_path, $video_duration, $video_url, Utils::time(), 0, $channel_id, $visibility_id]);
            }
            else {
                Response::get()->addError('upload', 'Unknown error while uploading the file');
                Response::get()->setSuccess(false);

                HTTPError::BadRequest();
            }
        }
        else {
            Response::get()->addError('validation', $validation->getErrors());
            Response::get()->setSuccess(false);

            HTTPError::BadRequest();
        }
    }

    public static function fetch() {
        // TODO: Implement fetch() method.
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