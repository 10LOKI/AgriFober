/// Non-web platforms never use the fallback; secure storage always works.
Future<String?> fallbackRead(String key) async => null;
Future<void> fallbackWrite(String key, String value) async {}
Future<void> fallbackClear(String key) async {}
