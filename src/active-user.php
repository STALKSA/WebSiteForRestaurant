<?php

final class ActiveUser {
	static function init(int $id, string $email, string $login, bool $isAdmin) {
		$_SESSION['user-id'] = $id;
		$_SESSION['user-email'] = $email;
		$_SESSION['user-login'] = $login;
		$_SESSION['is-admin'] = $isAdmin;
	}

	static function getID(): int | null {
		return isset($_SESSION['user-id']) ? (int) $_SESSION['user-id'] : null;
	}

	static function getEmail(): string | null {
		return isset($_SESSION['user-email']) ? $_SESSION['user-email'] : null;
	}

	static function getLogin(): string | null {
		return isset($_SESSION['user-login']) ? $_SESSION['user-login'] : null;
	}

	static function isAdmin(): bool | null {
		return isset($_SESSION['is-admin']) ? (bool) $_SESSION['is-admin'] : null;
	}
}
