import 'dart:io' show Platform;

import 'package:flutter/foundation.dart' show kIsWeb;

/// Central place for environment configuration.
///
/// Override the base URL at build/run time with:
///   flutter run --dart-define=API_BASE_URL=http://192.168.1.20:8000/api
class Env {
  Env._();

  /// API base URL, including the `/api` prefix used by Laravel's routes/api.php.
  static String get apiBaseUrl {
    const fromDefine = String.fromEnvironment('API_BASE_URL');
    if (fromDefine.isNotEmpty) return fromDefine;
    return '$_host/api';
  }

  /// Default host resolution:
  /// - Android emulator reaches the host machine via 10.0.2.2 (not localhost).
  /// - iOS simulator / web / desktop can use localhost directly.
  static String get _host {
    if (kIsWeb) return 'http://127.0.0.1:8000';
    if (Platform.isAndroid) return 'http://10.0.2.2:8000';
    return 'http://127.0.0.1:8000';
  }
}
