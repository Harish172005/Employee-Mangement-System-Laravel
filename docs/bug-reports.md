# Employee Management System — Bug Reports

**Purpose:** Track issues encountered during development and testing.  
**Evidence note:** The issues below are based on previously discussed EMS symptoms. Exact dates, reproduction logs, final fixes, and regression-test results should be confirmed before reporting them as formally closed defects.

## BUG-01 — Employee edit incorrectly requires user-registration fields

- **Severity:** High
- **Area:** Employee update / Form Request validation
- **Reported symptom:** Editing an employee displayed errors requiring username, email, role, status, and password even when the edit form was intended to update employee-specific fields.
- **Reproduction:** Log in as admin → open employee edit form → change an employee field → submit without registration-only fields.
- **Expected:** Only fields required by the edit workflow are validated.
- **Observed:** Validation messages demanded unrelated user-registration fields.
- **Fix approach:** Use a dedicated `EditEmployeeRequest`/`UpdateEmployeeRequest` with update-specific rules; keep user-registration validation in the creation request.
- **Status:** Fix discussed; confirm implementation and regression test.
- **Regression test:** TC-23.

## BUG-02 — Database notifications not appearing

- **Severity:** Medium
- **Area:** Notifications
- **Reported symptom:** The `notifications` table initially had no new records after expected notification actions.
- **Reproduction:** Update employee → inspect `notifications` table for the recipient user.
- **Expected:** Database-channel notification inserted with UUID, recipient morph fields, JSON data, and null `read_at`.
- **Observed:** No matching record during initial investigation.
- **Fix / checks:** Ensure `Notifiable` on recipient User; `via()` returns `database`; `notify()` executes after a successful update; recipient relation is non-null; notification migration is applied. Check logs/queue behavior if `ShouldQueue` is introduced.
- **Status:** Cause and final fix not independently verified.
- **Regression test:** Additional notification test.

## BUG-03 — Employee listing page takes unusually long to load

- **Severity:** Medium
- **Area:** Performance
- **Reported symptom:** Employee listing requests were observed around 870–1,112 ms in Laravel Telescope. A cached listing was also reported around 976 ms.
- **Reproduction:** Open `/admin/employees` repeatedly → inspect Telescope request duration and queries.
- **Expected:** Stable response time appropriate to data size and local environment; cache improves the targeted bottleneck.
- **Observed:** Page duration remained high despite a cache attempt.
- **Fix / checks:** Profile request timeline, Blade rendering, session writes, middleware, Telescope overhead, query count, cache hit/miss, and pagination. Avoid caching only a repository query if the time is elsewhere.
- **Status:** Under investigation; no confirmed improvement claimed.
- **Regression test:** Performance-report benchmark.

## BUG-04 — Employee welcome email job fails due to missing view

- **Severity:** Medium
- **Area:** Queue / Mail
- **Reported symptom:** `View [view.name] not found` occurred during welcome email implementation.
- **Reproduction:** Create employee → run queue worker → inspect failed job/log output.
- **Expected:** Mail template resolves and welcome email is sent by the worker.
- **Observed:** Mail rendering failed because a referenced view was unavailable or incorrectly named.
- **Fix:** Point the Mailable to the existing Blade view (e.g. `emails.employee_welcome`) and ensure its file exists at `resources/views/emails/employee_welcome.blade.php`.
- **Status:** Previously discussed as fixed; rerun end-to-end mail test.
- **Regression test:** Additional queued-mail test.

## BUG-05 — Employee creation throws service exception

- **Severity:** High
- **Area:** Employee creation / Service / Repository
- **Reported symptom:** Employee creation reached `ServiceException` during development.
- **Reproduction:** Submit valid employee creation form → observe exception and inspect application log for underlying exception.
- **Expected:** User and employee created atomically, with uploaded file retained only on success.
- **Observed:** Generic service exception reported; precise underlying cause not established here.
- **Fix / checks:** Inspect original exception (`previous`/logs), validate payload, verify user/employee unique constraints and foreign keys, keep `DB::transaction()`, and delete newly uploaded photo on failure. Do not treat the generic wrapper as the root cause.
- **Status:** Final root cause and resolution require confirmation.
- **Regression test:** TC-10 plus rollback test.

## Bug tracking summary

| Bug | Severity | Closure evidence |
|---|---|---|
| BUG-01 | High | Pending regression evidence |
| BUG-02 | Medium | Pending root-cause confirmation |
| BUG-03 | Medium | Pending benchmark comparison |
| BUG-04 | Medium | Fix discussed; pending end-to-end rerun |
| BUG-05 | High | Pending underlying exception and regression evidence |

**Before submission:** Attach screenshots or log excerpts, exact environment, discovery date, actual fix commit, retest date, and tester for each bug.
