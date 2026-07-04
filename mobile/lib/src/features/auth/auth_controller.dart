import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../models/user.dart';
import 'auth_repository.dart';

/// High-level auth lifecycle state for routing/UI.
enum AuthStatus { unknown, unauthenticated, authenticated }

class AuthState {
  const AuthState({required this.status, this.user});

  final AuthStatus status;
  final User? user;

  const AuthState.unknown() : this(status: AuthStatus.unknown);
  const AuthState.signedOut() : this(status: AuthStatus.unauthenticated);
  AuthState.signedIn(User user)
      : this(status: AuthStatus.authenticated, user: user);
}

final authControllerProvider =
    StateNotifierProvider<AuthController, AuthState>((ref) {
  return AuthController(ref.watch(authRepositoryProvider))..bootstrap();
});

class AuthController extends StateNotifier<AuthState> {
  AuthController(this._repo) : super(const AuthState.unknown());

  final AuthRepository _repo;

  /// On app start: if a token exists, validate it by fetching the user.
  Future<void> bootstrap() async {
    if (!await _repo.hasToken()) {
      state = const AuthState.signedOut();
      return;
    }
    try {
      final user = await _repo.currentUser();
      state = AuthState.signedIn(user);
    } catch (_) {
      // Stale/invalid token -> treat as signed out.
      state = const AuthState.signedOut();
    }
  }

  Future<void> login(String email, String password) async {
    final user = await _repo.login(email, password);
    state = AuthState.signedIn(user);
  }

  Future<void> register(Map<String, dynamic> payload) async {
    final user = await _repo.register(payload);
    state = AuthState.signedIn(user);
  }

  Future<void> logout() async {
    await _repo.logout();
    state = const AuthState.signedOut();
  }
}
