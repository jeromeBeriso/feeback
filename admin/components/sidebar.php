<div class="col-md-3 col-lg-2 sidebar  text-white d-none d-md-block" id="sidebar-menu">
    <h1 class="text-center mb-1"><img src="../logo.png" alt="logo" width="95%"></h1>
    <h6 class="text-center mb-4">Saint Vincent De Ferrer</h6>

    <ul class="nav flex-column ">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="statistics.php">Statistics</a></li>
        <li>
            <button
                class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between"
                type="button" data-bs-toggle="collapse" data-bs-target="#teacher">
                <span>Teacher</span>
                <span><i class="bi bi-caret-down-fill"></i></span>
            </button>
            <div class="collapse px-3 small mb-1" id="teacher">
                <ul class="nav nav-pills flex-column rounded border border-light active-light-green">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="teacher_record.php">Teacher's Record(s)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="teacher_page.php">Teacher</a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <button
                class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between"
                type="button" data-bs-toggle="collapse" data-bs-target="#student">
                <span>Student</span>
                <span><i class="bi bi-caret-down-fill"></i></span>
            </button>
            <div class="collapse px-3 small mb-1" id="student">
                <ul class="nav nav-pills flex-column rounded border border-light active-light-green">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="student_record.php">Student's Record(s)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="student_page.php">Student</a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <button
                class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between"
                type="button" data-bs-toggle="collapse" data-bs-target="#fac">
                <span>Faculty</span>
                <span><i class="bi bi-caret-down-fill"></i></span>
            </button>
            <div class="collapse px-3 small mb-1" id="fac">
                <ul class="nav nav-pills flex-column rounded border border-light active-light-green">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="faculty_record.php">Faculty Record(s)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="faculty.php">Faculty</a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <button
                class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between"
                type="button" data-bs-toggle="collapse" data-bs-target="#sub">
                <span>Subject</span>
                <span><i class="bi bi-caret-down-fill"></i></span>
            </button>
            <div class="collapse px-3 small mb-1" id="sub">
                <ul class="nav nav-pills flex-column rounded border border-light active-light-green">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="subject_record.php">Subject Record(s)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="subject.php">Subject</a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <button
                class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between"
                type="button" data-bs-toggle="collapse" data-bs-target="#feedback">
                <span>Feedbacks</span>
                <span><i class="bi bi-caret-down-fill"></i></span>
            </button>
            <div class="collapse px-3 small mb-1" id="feedback">
                <ul class="nav nav-pills flex-column rounded border border-light active-light-green">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="record.php">Feedback's Record(s)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="session.php">Feedback</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>

<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column ">
            <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Statistics</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Faculty</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Feedbacks</a></li>
        </ul>
    </div>
</div>