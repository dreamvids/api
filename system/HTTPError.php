<?php
abstract class HTTPError {
	public static function error400(): Response {
		return new Response(400);
	}

	public static function error401(): Response {
		return new Response(401);
	}

	public static function error403(): Response {
		return new Response(403);
	}

	public static function error404(): Response {
		return new Response(404);
	}

	public static function error405(): Response {
		return new Response(405);
	}

	public static function error500(): Response {
		return new Response(500);
	}
}