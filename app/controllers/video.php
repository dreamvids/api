<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 02/07/2016
 * Time: 12:52
 */

class VideoCtrl implements ControllerInterface {
    use ExistsTrait;

    const SUPPORTED_VIDEO_EXTENSIONS = [ //TODO
        'mp4', 'avi', 'webm', 'htm'
    ];

    const SUPPORTED_THUMBNAIL_EXTENSIONS = [ //TODO
        'jpeg', 'jpg', 'png'
    ];

    const MAX_VIDEO_SIZE = 2*10e9; //TODO change
    const MAX_THUMBNAIM_SIZE = 5*10e6; //TODO change

    public static function create() {
        $validation = new Validator([
            'title' => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'Title required'
                ]
            ],
            'description' => [],
            'video' => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_TYPE => Validator::TYPE_FILE,
                Validator::PARAM_FILE_EXTENSION => self::SUPPORTED_VIDEO_EXTENSIONS,
                Validator::PARAM_FILE_SIZE=> self::MAX_VIDEO_SIZE,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'A video file is (obviously) required...',
                    Validator::PARAM_TYPE => 'Error while uploading video',
                    Validator::PARAM_FILE_EXTENSION => "The supported extensions are : " . implode(", ",self::SUPPORTED_VIDEO_EXTENSIONS),
                    Validator::PARAM_FILE_SIZE=> "Video too big."
                ]
            ],
            'thumbnail' => [
                Validator::PARAM_REQUIRED => false,
                Validator::PARAM_TYPE => Validator::TYPE_FILE,
                Validator::PARAM_FILE_EXTENSION => self::SUPPORTED_THUMBNAIL_EXTENSIONS,
                Validator::PARAM_FILE_SIZE=> self::MAX_VIDEO_SIZE,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'A video file is (obviously) required...',
                    Validator::PARAM_TYPE => 'Error while uploading thumbnail',
                    Validator::PARAM_FILE_EXTENSION => "The supported extensions are : " . implode(", ",self::SUPPORTED_VIDEO_EXTENSIONS),
                    Validator::PARAM_FILE_SIZE=> "Video too big."
                ]
            ]
        ]);


        if($validation->validate()) {
            $vidId = Utils::generateId();
            $basedir = System::get()->getRoot().'uploads/';
            $video_ext = $validation->data("video.extension");
            $video_path = $basedir.$vidId.'.'.$video_ext;

            if ($thumbnail = $validation->data("thumbnail")) {
                $thumbnail_ext = $thumbnail['extension'];
                $thumbnail_path = $basedir.$vidId.'.'.$thumbnail_ext;
                //TODO: Copier la miniature sur les serveurs de stockage puis en récup l'URL
                $thumbnail_path = 'http://.../'.$vidId.'.'.$thumbnail_ext;
            }
            else {
                $thumbnail_path = System::get()->getWebroot().'assets/img/defaultThumbnail.png';
            }

            if (move_uploaded_file($validation->data('video.tmp_name'), $video_path)) {
                // TODO: Set video duration
                $video_duration = 0;
                // TODO: POST conversion.dreamvids.fr/
                $video_url = 'http://.../video/'.$vidId.'.'.$video_ext;
                // TODO: get channel_id & visibility_id
                $channel_id = 0;
                $visibility_id = 0;
                Video::insertIntoDb([$validation->data('title'), $validation->data('description'), $thumbnail_path, $video_duration, $video_url, Utils::time(), 0, $channel_id, $visibility_id]);
            }
            else {
                error_log(var_export(error_get_last(), true));
                Response::get()->addError('upload', 'Unknown error while uploading the file');
                Response::get()->setSuccess(false);

                HTTPError::InternalServerError();
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