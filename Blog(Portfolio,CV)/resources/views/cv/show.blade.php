@extends('layouts.app')

@section('title', $cv->full_name . ' - CV')

@section('content')
<style>
    :root {
        --primary: #2563eb;
        --secondary: #1e40af;
        --accent: #0891b2;
        --light: #f8fafc;
        --dark: #1e293b;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 40px 20px;
    }

    .cv-container {
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    /* Header */
    .cv-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        color: white;
        padding: 60px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cv-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .cv-header-content {
        position: relative;
        z-index: 1;
    }

    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        margin: 0 auto 30px;
        object-fit: cover;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .cv-header h1 {
        font-size: 2.5em;
        margin: 20px 0;
        font-weight: 700;
    }

    .cv-title {
        font-size: 1.5em;
        opacity: 0.95;
        margin-bottom: 20px;
        font-weight: 300;
    }

    .cv-bio {
        font-size: 1em;
        opacity: 0.9;
        max-width: 600px;
        margin: 20px auto;
        line-height: 1.6;
    }

    .social-links {
        margin-top: 25px;
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.2em;
    }

    .social-links a:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: translateY(-3px);
    }

    /* Body */
    .cv-body {
        padding: 50px 40px;
    }

    .cv-section {
        margin-bottom: 50px;
    }

    .section-title {
        font-size: 1.8em;
        color: var(--primary);
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid var(--primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        font-size: 1.2em;
    }

    /* About Section */
    .about-text {
        font-size: 1.1em;
        line-height: 1.8;
        color: #333;
        text-align: justify;
    }

    .quick-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .info-card {
        background: var(--light);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid var(--primary);
    }

    .info-card-label {
        font-size: 0.9em;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .info-card-value {
        font-size: 1.1em;
        color: var(--dark);
        font-weight: 600;
        word-break: break-word;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, var(--primary), var(--accent));
    }

    .timeline-item {
        margin-left: 60px;
        margin-bottom: 40px;
        position: relative;
    }

    .timeline-dot {
        position: absolute;
        left: -50px;
        top: 5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 4px solid var(--primary);
        box-shadow: 0 0 0 8px var(--light);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 10px;
    }

    .timeline-company, .timeline-school {
        font-size: 1.3em;
        font-weight: 700;
        color: var(--primary);
    }

    .timeline-position, .timeline-degree {
        font-size: 1.1em;
        color: #333;
        font-weight: 600;
        margin-top: 5px;
    }

    .timeline-date {
        font-size: 0.95em;
        color: #999;
        font-style: italic;
    }

    .timeline-duration {
        background: var(--light);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9em;
        color: #666;
    }

    .timeline-description {
        color: #555;
        line-height: 1.6;
        margin-top: 10px;
    }

    .achievements {
        margin-top: 15px;
        list-style: none;
    }

    .achievements li {
        padding: 8px 0;
        padding-left: 25px;
        position: relative;
        color: #555;
        line-height: 1.5;
    }

    .achievements li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--accent);
        font-weight: bold;
        font-size: 1.2em;
    }

    /* Skills */
    .skills-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .skill-category {
        margin-bottom: 30px;
    }

    .skill-category-title {
        font-size: 1.2em;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .skill-item {
        margin-bottom: 20px;
    }

    .skill-name {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .skill-level {
        font-size: 0.85em;
        color: #999;
        text-transform: uppercase;
    }

    .skill-bar {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .skill-progress {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    /* Projects */
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .project-card {
        background: var(--light);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .project-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        border-color: var(--primary);
    }

    .project-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5em;
    }

    .project-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .project-body {
        padding: 25px;
    }

    .project-name {
        font-size: 1.3em;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .project-date {
        font-size: 0.9em;
        color: #999;
        margin-bottom: 12px;
    }

    .project-description {
        color: #555;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .project-tech {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 15px;
    }

    .tech-tag {
        display: inline-block;
        background: var(--primary);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }

    .project-links {
        display: flex;
        gap: 10px;
    }

    .project-link {
        flex: 1;
        text-align: center;
        padding: 10px;
        background: white;
        color: var(--primary);
        text-decoration: none;
        border-radius: 8px;
        border: 2px solid var(--primary);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .project-link:hover {
        background: var(--primary);
        color: white;
    }

    /* Footer */
    .cv-footer {
        background: var(--light);
        padding: 30px 40px;
        text-align: center;
        color: #666;
        border-top: 2px solid #e2e8f0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cv-header {
            padding: 40px 20px;
        }

        .cv-header h1 {
            font-size: 1.8em;
        }

        .cv-body {
            padding: 30px 20px;
        }

        .timeline::before {
            left: 5px;
        }

        .timeline-item {
            margin-left: 40px;
        }

        .timeline-dot {
            left: -35px;
        }

        .quick-info {
            grid-template-columns: 1fr;
        }

        .skills-container {
            grid-template-columns: 1fr;
        }

        .projects-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="cv-container">
    <!-- Header -->
    <div class="cv-header">
        <div class="cv-header-content">
            @if($cv->profile_photo)
                <img src="{{ asset('storage/' . $cv->profile_photo) }}" alt="{{ $cv->full_name }}" class="profile-photo">
            @else
                <div class="profile-photo" style="background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1)); display: flex; align-items: center; justify-content: center; font-size: 3em;">
                    <i class="fas fa-user"></i>
                </div>
            @endif

            <h1>{{ $cv->full_name }}</h1>
            @if($cv->title)
                <div class="cv-title">{{ $cv->title }}</div>
            @endif
            @if($cv->bio)
                <div class="cv-bio">{{ $cv->bio }}</div>
            @endif

            <div class="social-links">
                @if($cv->linkedin_url)
                    <a href="{{ $cv->linkedin_url }}" target="_blank" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                @endif
                @if($cv->github_url)
                    <a href="{{ $cv->github_url }}" target="_blank" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                @endif
                @if($cv->twitter_url)
                    <a href="{{ $cv->twitter_url }}" target="_blank" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                @endif
                @if($cv->website_url)
                    <a href="{{ $cv->website_url }}" target="_blank" title="Website">
                        <i class="fas fa-globe"></i>
                    </a>
                @endif
                @if($cv->portfolio_url)
                    <a href="{{ $cv->portfolio_url }}" target="_blank" title="Portfolio">
                        <i class="fas fa-briefcase"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="cv-body">
        <!-- About Section -->
        @if($cv->about_me)
            <div class="cv-section">
                <h2 class="section-title">
                    <i class="fas fa-user-circle"></i>
                    About Me
                </h2>
                <div class="about-text">
                    {{ $cv->about_me }}
                </div>
            </div>
        @endif

        <!-- Quick Info -->
        <div class="quick-info">
            @if($cv->email)
                <div class="info-card">
                    <div class="info-card-label">Email</div>
                    <div class="info-card-value">
                        <a href="mailto:{{ $cv->email }}" style="color: inherit; text-decoration: none;">
                            {{ $cv->email }}
                        </a>
                    </div>
                </div>
            @endif

            @if($cv->phone)
                <div class="info-card">
                    <div class="info-card-label">Phone</div>
                    <div class="info-card-value">
                        <a href="tel:{{ $cv->phone }}" style="color: inherit; text-decoration: none;">
                            {{ $cv->phone }}
                        </a>
                    </div>
                </div>
            @endif

            @if($cv->location)
                <div class="info-card">
                    <div class="info-card-label">Location</div>
                    <div class="info-card-value">{{ $cv->location }}</div>
                </div>
            @endif

            @if($cv->experiences->count())
                <div class="info-card">
                    <div class="info-card-label">Experience</div>
                    <div class="info-card-value">{{ $cv->getYearsOfExperience() }}+ Years</div>
                </div>
            @endif

            <div class="info-card">
                <div class="info-card-label">Skills</div>
                <div class="info-card-value">{{ $cv->skills->count() }}</div>
            </div>

            <div class="info-card">
                <div class="info-card-label">Projects</div>
                <div class="info-card-value">{{ $cv->projects->count() }}</div>
            </div>
        </div>

        <!-- Experience Section -->
        @if($cv->experiences->count())
            <div class="cv-section">
                <h2 class="section-title">
                    <i class="fas fa-briefcase"></i>
                    Professional Experience
                </h2>
                <div class="timeline">
                    @foreach($cv->experiences as $exp)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-header">
                                <div>
                                    <div class="timeline-company">{{ $exp->company_name }}</div>
                                    <div class="timeline-position">{{ $exp->job_title }}</div>
                                    @if($exp->employment_type)
                                        <small style="color: #999;">{{ $exp->employment_type }}</small>
                                    @endif
                                </div>
                                <div class="timeline-duration">{{ $exp->display_date }}</div>
                            </div>
                            @if($exp->location)
                                <small style="color: #999;">📍 {{ $exp->location }}</small>
                            @endif
                            @if($exp->description)
                                <p class="timeline-description">{{ $exp->description }}</p>
                            @endif
                            @if($exp->key_achievements)
                                <ul class="achievements">
                                    @foreach($exp->key_achievements as $achievement)
                                        <li>{{ $achievement }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Education Section -->
        @if($cv->educations->count())
            <div class="cv-section">
                <h2 class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    Education
                </h2>
                <div class="timeline">
                    @foreach($cv->educations as $edu)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-header">
                                <div>
                                    <div class="timeline-school">{{ $edu->school_name }}</div>
                                    <div class="timeline-degree">{{ $edu->full_qualification }}</div>
                                </div>
                                <div class="timeline-duration">{{ $edu->display_date }}</div>
                            </div>
                            @if($edu->gpa)
                                <small style="color: #999;">GPA: {{ $edu->gpa }}</small>
                            @endif
                            @if($edu->description)
                                <p class="timeline-description">{{ $edu->description }}</p>
                            @endif
                            @if($edu->activities)
                                <ul class="achievements">
                                    @foreach($edu->activities as $activity)
                                        <li>{{ $activity }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Skills Section -->
        @if($cv->skills->count())
            <div class="cv-section">
                <h2 class="section-title">
                    <i class="fas fa-star"></i>
                    Skills & Expertise
                </h2>
                <div class="skills-container">
                    @php
                        $skillsByCategory = $cv->skills->groupBy('category');
                    @endphp
                    @forelse($skillsByCategory as $category => $skills)
                        <div class="skill-category">
                            <div class="skill-category-title">{{ $category ?? 'General' }}</div>
                            @foreach($skills as $skill)
                                <div class="skill-item">
                                    <div class="skill-name">
                                        <span>{{ $skill->skill_name }}</span>
                                        <span class="skill-level">{{ $skill->proficiency_level }}</span>
                                    </div>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: {{ $skill->proficiency }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p>No skills added yet.</p>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Projects Section -->
        @if($cv->projects->count())
            <div class="cv-section">
                <h2 class="section-title">
                    <i class="fas fa-code"></i>
                    Featured Projects
                </h2>
                <div class="projects-grid">
                    @foreach($cv->projects as $project)
                        <div class="project-card">
                            <div class="project-image">
                                @if($project->featured_image)
                                    <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->project_name }}">
                                @else
                                    <i class="fas fa-project-diagram"></i>
                                @endif
                            </div>
                            <div class="project-body">
                                <h3 class="project-name">{{ $project->project_name }}</h3>
                                <div class="project-date">{{ $project->display_date }}</div>
                                <p class="project-description">{{ $project->description }}</p>
                                @if($project->technologies)
                                    <div class="project-tech">
                                        @foreach($project->technologies as $tech)
                                            <span class="tech-tag">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="project-links">
                                    @if($project->project_url)
                                        <a href="{{ $project->project_url }}" target="_blank" class="project-link">
                                            Live Project
                                        </a>
                                    @endif
                                    @if($project->github_url)
                                        <a href="{{ $project->github_url }}" target="_blank" class="project-link">
                                            View Code
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Submitted / simple portfolio entries (from storage/app/portfolio.json) --}}
        @if(isset($entries) && count($entries) > 0)
            <div class="cv-section">
                <h2 class="section-title">
                    <i class="fas fa-clipboard-list"></i>
                    Submitted / Community Portfolio
                </h2>

                <div class="projects-grid">
                    @foreach($entries as $entry)
                        <div class="project-card">
                            <div class="project-image">
                                <i class="fas fa-user-briefcase"></i>
                            </div>
                            <div class="project-body">
                                <h3 class="project-name">{{ $entry['name'] ?? 'Unnamed' }}</h3>
                                @if(!empty($entry['title']))
                                    <div class="project-date">{{ $entry['title'] }}</div>
                                @endif
                                @if(!empty($entry['bio']))
                                    <p class="project-description">{{ $entry['bio'] }}</p>
                                @endif
                                @if(!empty($entry['website']))
                                    <div class="project-links">
                                        <a href="{{ $entry['website'] }}" target="_blank" class="project-link">Website</a>
                                    </div>
                                @endif
                                <div style="margin-top:8px; font-size:0.85em; color:#999;">Submitted: {{ $entry['created_at'] ?? '' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Community portfolio entries from database (approved) --}}
        @php
            $communityProjects = $cv->projects->where('submission_type', 'community')->where('is_approved', true);
        @endphp
        @if($communityProjects->count() > 0)
            <div class="cv-section">
                <h2 class="section-title">
                    <i class="fas fa-users"></i>
                    Community Contributions
                </h2>
                <div class="projects-grid">
                    @foreach($communityProjects as $project)
                        <div class="project-card">
                            <div class="project-image">
                                @if($project->featured_image)
                                    <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->project_name }}">
                                @else
                                    <i class="fas fa-user-circle"></i>
                                @endif
                            </div>
                            <div class="project-body">
                                <h3 class="project-name">{{ $project->project_name }}</h3>
                                <div class="project-date">by {{ $project->submitted_by ?? 'Anonymous' }}</div>
                                @if($project->description)
                                    <p class="project-description">{{ $project->description }}</p>
                                @endif
                                @if($project->detailed_description)
                                    <p class="project-description" style="font-size: 0.9em;">{{ $project->detailed_description }}</p>
                                @endif
                                @if($project->technologies)
                                    <div class="project-tech">
                                        @foreach($project->technologies as $tech)
                                            <span class="tech-tag">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="project-links">
                                    @if($project->project_url)
                                        <a href="{{ $project->project_url }}" target="_blank" class="project-link">Visit</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="cv-footer">
        <p>&copy; {{ date('Y') }} {{ $cv->full_name }}. All rights reserved.</p>
        <p style="margin: 10px 0 0; font-size: 0.9em;">
            Last Updated: {{ $cv->updated_at->format('M d, Y') }}
        </p>
    </div>
</div>
@endsection
