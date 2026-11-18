# Employee Management System — Security Checklist

**Scope:** Laravel EMS web routes, employee API search endpoint, uploads, authentication, authorization, and production configuration.

Legend: **Implemented/discussed** = present in the EMS implementation described during development; **Verify** = test or inspect current source; **Recommended** = not confirmed implemented.

| ID | Control | Implementation / verification action | Status |
|---|---|---|---|
| SEC-01 | Authentication | Apply `auth` middleware to protected web/admin routes | Implemented/discussed; verify |
| SEC-02 | Active account restriction | Use `active` middleware and verify inactive accounts cannot access routes | Implemented/discussed; verify logic |
| SEC-03 | Role-based authorization | Apply `role:admin` to admin endpoints | Implemented/discussed; verify |
| SEC-04 | Policy/Gate authorization | Use `can:manage-employees` and policy checks on resource actions | Implemented/discussed; verify every action |
| SEC-05 | IDOR protection | Check record-level authorization on show/update/delete/API requests | Verify |
| SEC-06 | CSRF protection | Keep web middleware CSRF verification and `@csrf` in state-changing forms | Implemented/discussed; verify |
| SEC-07 | Server-side validation | Validate create/update/filter input with Form Requests | Implemented/discussed; verify rules |
| SEC-08 | Mass assignment | Maintain restricted `$fillable` on models; never pass unrestricted request arrays to `create()` | Implemented/discussed; verify |
| SEC-09 | Password hashing | Hash new account passwords; never log or email plaintext passwords | Implemented/discussed; verify mail content |
| SEC-10 | SQL injection prevention | Use Eloquent parameter binding; allowlist sort column and direction; avoid concatenated raw SQL | Implemented/discussed; verify |
| SEC-11 | XSS prevention | Use escaped `{{ }}` output; review `{!! !!}` and user-provided HTML | Verify |
| SEC-12 | Upload validation | Restrict MIME/content, size, and extension; generate safe names | Verify |
| SEC-13 | Upload storage | Store images on configured disk; avoid executable uploads; verify public access scope | Implemented/discussed; verify |
| SEC-14 | Secure deletion | Confirm soft-deleted data is excluded from unauthorized views and APIs | Verify |
| SEC-15 | API authentication | Confirm web/session authentication is intentional for the employee search API | Implemented/discussed; verify |
| SEC-16 | API authorization | Enforce admin role and employee permission on search API | Implemented/discussed; verify |
| SEC-17 | Rate limiting | Apply `throttle:api` to API endpoint and test limit | Implemented/discussed; verify |
| SEC-18 | Error disclosure | Set `APP_DEBUG=false` in production; avoid exposing exception traces | Recommended for deployment |
| SEC-19 | Secrets management | Keep `.env` out of version control; rotate exposed credentials; maintain `.env.example` | Verify |
| SEC-20 | Session security | Use secure cookies and HTTPS in production; invalidate session on logout | Verify |
| SEC-21 | Authorization tests | Automate negative tests for guest, inactive, employee, and admin roles | Recommended |
| SEC-22 | Logging/privacy | Avoid sensitive fields in activity logs, Telescope, email, and notifications | Verify |
| SEC-23 | Dependency hygiene | Review Composer advisories and update vulnerable dependencies | Recommended |
| SEC-24 | Queue and scheduler safety | Restrict operational endpoints and logs; protect mail credentials | Verify |

## Priority verification steps

1. Confirm every employee and department mutation has a policy or authorization check, not only a hidden UI button.
2. Confirm the employee search API cannot return private data to unauthorized users.
3. Review upload validation and storage paths, including replacement and failure cleanup.
4. Verify `APP_DEBUG=false`, HTTPS, secure cookies, and secrets handling before deployment.
5. Execute negative tests for CSRF, IDOR, XSS, invalid enum input, and rate limiting.

## Security test evidence

| Test | Result | Evidence / date |
|---|---|---|
| Unauthorized employee accessing admin route | Pending | — |
| Unauthenticated API access | Pending | — |
| Cross-user employee record access | Pending | — |
| Invalid CSRF token | Pending | — |
| Script input rendered safely | Pending | — |
| Invalid file upload rejected | Pending | — |
| Rate limit enforced | Pending | — |

**Important:** This is an implementation-and-verification checklist, not a claim of a completed security audit.
