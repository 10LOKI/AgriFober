// Compiled only on web via conditional import; the lint doesn't see that.
// ignore_for_file: avoid_web_libraries_in_flutter, deprecated_member_use
import 'dart:html' as html;

/// Plain localStorage fallback for insecure origins (plain-HTTP LAN demos),
/// where flutter_secure_storage's WebCrypto backend is unavailable.
Future<String?> fallbackRead(String key) async =>
    html.window.localStorage[key];

Future<void> fallbackWrite(String key, String value) async {
  html.window.localStorage[key] = value;
}

Future<void> fallbackClear(String key) async {
  html.window.localStorage.remove(key);
}
