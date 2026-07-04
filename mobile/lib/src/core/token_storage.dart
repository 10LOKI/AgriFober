import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import 'web_fallback_storage_stub.dart'
    if (dart.library.html) 'web_fallback_storage_web.dart' as fallback;

/// Persists the Sanctum bearer token in the platform secure store
/// (Keychain on iOS, EncryptedSharedPreferences on Android).
///
/// On web served from an insecure origin (plain-HTTP LAN demo), the secure
/// store's WebCrypto backend is unavailable and throws — fall back to
/// localStorage so login still works.
class TokenStorage {
  TokenStorage([FlutterSecureStorage? storage])
      : _storage = storage ?? const FlutterSecureStorage();

  static const _key = 'api_token';

  final FlutterSecureStorage _storage;

  Future<String?> read() async {
    String? value;
    try {
      value = await _storage.read(key: _key);
    } catch (_) {
      // Secure store unusable on this origin; fallback below.
    }
    // A missing value doesn't throw, so always consult the fallback too.
    return value ?? await fallback.fallbackRead(_key);
  }

  Future<void> write(String token) async {
    try {
      await _storage.write(key: _key, value: token);
    } catch (_) {
      await fallback.fallbackWrite(_key, token);
    }
  }

  Future<void> clear() async {
    try {
      await _storage.delete(key: _key);
    } catch (_) {}
    await fallback.fallbackClear(_key);
  }
}
