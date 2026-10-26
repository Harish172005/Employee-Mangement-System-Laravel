Copy all .php files in this folder to app/Http/Controllers/ (overwrite).
Then run:  php artisan view:clear && php artisan route:clear

Method -> view map
  AuthController::showLogin          auth.login
  DashboardController::__invoke      dashboard                (stats, byDepartment)
  EmployeeController::index          employees.index          (employees [paginator], search)
  EmployeeController::create         employees.create         (departments via AppServiceProvider composer)
  EmployeeController::show           employees.show           (employee)
  EmployeeController::edit           employees.edit           (employee)
  EmployeeController::search         JSON  (used by employee-search.js)
  EmployeeController::export         CSV download
  DepartmentController::index        departments.index        (departments, search)
  DepartmentController::create       departments.create
  DepartmentController::show         departments.show         (department)
  DepartmentController::edit         departments.edit         (department)
  UserController::index              users.index              (users)
  UserController::create             users.create
  UserController::edit               users.edit               (user)
  ProfileController::show            profile.show             (employee)
  ProfileController::edit            profile.edit             (employee)
  ProfileController::department      profile.department       (department)
  ProfileController::editPassword    profile.password
  ReportController::__invoke         reports                  (report)

store / update / destroy methods redirect with a flash message (no view).
