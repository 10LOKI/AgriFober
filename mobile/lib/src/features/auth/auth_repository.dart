import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/providers.dart';
import '../../core/token_storage.dart';
import '../../models/user.dart';

final authRepositoryProvider = Provider<AuthRepository>((ref) {
  return AuthRepository(
    api: ref.watch(apiClientProvider),
    tokenStorage: ref.watch(tokenStorageProvider),
  );
});

/// Talks to the Laravel auth endpoints in routes/api.php.
class AuthRepository {
  AuthRepository({required this.api, required this.tokenStorage});

  final ApiClient api;
  final TokenStorage tokenStorage;

  /// POST /login -> { user, token }. Persists the token on success.
  /// Throws ApiException(403) when the account is not yet approved.
  Future<User> login(String email, String password) async {
    final data = await api.post('/login', body: {
      'email': email,
      'password': password,
    });
    return _persistAndParse(data as Map<String, dynamic>);
  }

  /// POST /register -> { user, token }. Account starts unapproved.
  Future<User> register(Map<String, dynamic> payload) async {
    final data = await api.post('/register', body: payload);
    return _persistAndParse(data as Map<String, dynamic>);
  }

  /// GET /user -> current authenticated user (used on app boot).
  Future<User> currentUser() async {
    final data = await api.get('/user');
    return User.fromJson(data as Map<String, dynamic>);
  }

  Future<void> logout() async {
    try {
      await api.post('/logout');
    } finally {
      await tokenStorage.clear();
    }
  }

  Future<bool> hasToken() async {
    final token = await tokenStorage.read();
    return token != null && token.isNotEmpty;
  }

  Future<User> _persistAndParse(Map<String, dynamic> data) async {
    final token = data['token'] as String;
    await tokenStorage.write(token);
    return User.fromJson(data['user'] as Map<String, dynamic>);
  }
}
