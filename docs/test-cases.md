# Employee Management System — Test Cases

**Project:** Laravel Employee Management System (EMS)  
**Coverage:** Authentication, authorization, employee/department management, validation, uploads, search, pagination, API, notifications, and security  
**Execution status:** Not verified. Mark each case Pass/Fail only after executing it.  
**Test environment:** Local Windows/XAMPP/MySQL (confirm versions and test database before execution).

## Execution instructions

Use a dedicated test database and accounts for an active admin, active employee, inactive user, and unauthenticated visitor. Record the execution date, actual result, evidence (screenshot/log), and any linked bug ID. Do not run destructive tests against production data.

| ID | Area | Test case / action | Expected result | Status |
|---|---|---|---|---|
| TC-01 | Login | Log in with valid active admin credentials | Redirect to authorized dashboard | Not run |
| TC-02 | Login | Submit incorrect password | Login denied with safe error message | Not run |
| TC-03 | Login | Submit empty username/email and password | Required-field validation shown | Not run |
| TC-04 | Logout | Log out from authenticated session | Session invalidated; protected pages require login | Not run |
| TC-05 | Authentication | Visit admin route as guest | Redirect to login (web route) | Not run |
| TC-06 | Authentication | Attempt access using inactive user | Access denied according to active middleware policy | Not run |
| TC-07 | Authorization | Employee attempts admin employee listing | HTTP 403 or configured denial | Not run |
| TC-08 | Authorization | Admin accesses employee listing | Authorized page loads | Not run |
| TC-09 | Authorization | Employee attempts another employee's protected record | Access denied; no private data disclosed | Not run |
| TC-10 | Employee CRUD | Admin creates employee with valid details | Employee created and linked user created as designed | Not run |
| TC-11 | Employee CRUD | Admin opens employee details | Correct employee, department, and associated user displayed | Not run |
| TC-12 | Employee CRUD | Admin edits permitted employee fields | Updated values persist and display | Not run |
| TC-13 | Employee CRUD | Admin soft-deletes employee | `deleted_at` populated; excluded from default listing | Not run |
| TC-14 | Employee CRUD | Admin restores soft-deleted employee | `deleted_at` cleared; appears in default listing | Not run |
| TC-15 | Employee CRUD | View listing with `with_trashed=1` | Soft-deleted and non-deleted records included | Not run |
| TC-16 | Department CRUD | Admin creates department with valid data | Department saved and visible | Not run |
| TC-17 | Department CRUD | Admin views department details/list | Correct department data shown | Not run |
| TC-18 | Department CRUD | Admin updates department | Changes persist | Not run |
| TC-19 | Department CRUD | Admin deletes department with no restricted dependencies | Deletion follows application policy | Not run |
| TC-20 | Department CRUD | Delete department referenced by employees | Referential-integrity rule enforced; clear user-facing error | Not run |
| TC-21 | Validation | Create employee with required fields missing | Form Request errors displayed by field | Not run |
| TC-22 | Validation | Create employee with duplicate email | Duplicate rejected with validation error | Not run |
| TC-23 | Validation | Edit employee without user-account creation fields | Only fields required by edit workflow are validated | Not run |
| TC-24 | Validation | Submit invalid status/gender enum value | Request rejected; invalid value not persisted | Not run |
| TC-25 | File upload | Upload valid supported profile photo | File stored on configured public disk; URL displays image | Not run |
| TC-26 | File upload | Upload unsupported/oversized file | Validation rejects file; no unsafe file stored | Not run |
| TC-27 | File upload | Replace existing profile photo | New photo shown; previous file cleaned up per service logic | Not run |
| TC-28 | Search | Search employee by first/last name, email, or code | Matching employees returned | Not run |
| TC-29 | Search | Search using full name with whitespace | Matching first + last name returned | Not run |
| TC-30 | Filters | Combine department, status, gender, and sorting | Only matching records returned in correct order | Not run |
| TC-31 | Pagination | Navigate employee listing pages | Correct page size, count, and navigation | Not run |
| TC-32 | Pagination | Filter then navigate to next page | Filter and sort query parameters preserved | Not run |
| TC-33 | API | Authorized admin requests employee search endpoint | Valid JSON with expected results/pagination | Not run |
| TC-34 | API security | Unauthenticated or unauthorized user calls employee API | Request rejected with appropriate status; no records leaked | Not run |
| TC-35 | Security | Submit request without valid CSRF token to protected web POST route | HTTP 419 or configured CSRF failure | Not run |
| TC-36 | Security | Submit script payload in text field and display it | Payload escaped; script does not execute | Not run |

## Additional recommended tests

- Verify welcome email job dispatch after transaction commit and worker execution.
- Verify database notification record, recipient, and mark-as-read flow.
- Verify API throttling after the configured limit.
- Verify SQL injection attempts are handled as parameterized values.
- Verify database rollback and photo cleanup on failed employee creation.
- Verify locale switching between English and Tamil.

## Test execution summary

| Metric | Count |
|---|---:|
| Planned test cases | 36 |
| Passed | TBD |
| Failed | TBD |
| Blocked | TBD |
| Not run | 36 |

**Note:** The table documents planned verification, not completed test results. Update statuses after running each test.
