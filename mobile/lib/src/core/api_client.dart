import 'package:dio/dio.dart';

import 'api_exception.dart';
import 'env.dart';
import 'token_storage.dart';

/// Thin wrapper over Dio that:
/// - sets the base URL + JSON headers Laravel expects,
/// - injects the Sanctum bearer token on every request,
/// - unwraps the `{ success, data, message }` envelope,
/// - converts Dio errors into [ApiException].
class ApiClient {
  ApiClient({required TokenStorage tokenStorage, Dio? dio})
      : _tokenStorage = tokenStorage,
        _dio = dio ?? Dio() {
    _dio.options
      ..baseUrl = Env.apiBaseUrl
      ..connectTimeout = const Duration(seconds: 15)
      ..receiveTimeout = const Duration(seconds: 20)
      ..headers['Accept'] = 'application/json';

    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await _tokenStorage.read();
          if (token != null && token.isNotEmpty) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          handler.next(options);
        },
      ),
    );
  }

  final Dio _dio;
  final TokenStorage _tokenStorage;

  Future<dynamic> get(String path, {Map<String, dynamic>? query}) =>
      _request(() => _dio.get(path, queryParameters: query));

  Future<dynamic> post(String path, {Object? body}) =>
      _request(() => _dio.post(path, data: body));

  Future<dynamic> put(String path, {Object? body}) =>
      _request(() => _dio.put(path, data: body));

  Future<dynamic> delete(String path) =>
      _request(() => _dio.delete(path));

  /// Runs [send], unwraps the Laravel envelope, throws [ApiException] on failure.
  Future<dynamic> _request(Future<Response> Function() send) async {
    try {
      final res = await send();
      final data = res.data;
      // Laravel responses are wrapped in { success, data, message }.
      if (data is Map && data.containsKey('data')) {
        return data['data'];
      }
      return data;
    } on DioException catch (e) {
      throw _mapError(e);
    }
  }

  ApiException _mapError(DioException e) {
    final response = e.response;
    final data = response?.data;

    String message = 'Network error. Check your connection.';
    Map<String, List<String>>? errors;

    if (data is Map) {
      if (data['message'] is String) message = data['message'] as String;
      final raw = data['errors'];
      if (raw is Map) {
        errors = raw.map(
          (key, value) => MapEntry(
            key.toString(),
            (value as List).map((e) => e.toString()).toList(),
          ),
        );
      }
    }

    return ApiException(
      message,
      statusCode: response?.statusCode,
      errors: errors,
    );
  }
}
