<?php

/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 31/08/2016
 * Time: 12:56
 */
class PermChecker {
    private $is_permit;
    private $session;

    private function __construct() {
        $this->is_permit = true;
        $this->session = $GLOBALS['session'] ?? null;
        if ($this->session == null) {
            (new Response(Response::HTTP_403_FORBIDDEN))->render();
        }
    }

    public static function get(): PermChecker {
        return new self();
    }

    public function or(PermChecker $permChecker): PermChecker {
        $this->is_permit = ($permChecker->isItPermitted() || $this->is_permit);
        return $this;
    }

    public function rank($values): PermChecker {
        if (is_array($values)) {
            $perm = false;
            foreach ($values as $v) {
                if ($this->session->user->rank->getName() == $v) {
                    $perm = true;
                    break;
                }
            }
        }
        else {
            $perm = ($this->session->user->rank->getName() == $values);
        }

        $this->is_permit = $perm;
        return $this;
    }

    public function id($values): PermChecker {
        if (is_array($values)) {
            $perm = false;
            foreach ($values as $v) {
                if ($this->session->user->getId() == $v) {
                    $perm = true;
                    break;
                }
            }
        }
        else {
            $perm = ($this->session->user->getId() == $values);
        }

        $this->is_permit = $perm;
        return $this;
    }

    public function isPermit() {
        if ($this->is_permit) {
            return new Response();
        }
        (new Response(Response::HTTP_403_FORBIDDEN))->render();
    }

    /**
     * @return boolean
     */
    private function isItPermitted() {
        return $this->is_permit;
    }


}