@extends('layouts.app')

@section('title', 'CV Admin Dashboard')

@section('content')
<style>
    .admin-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .admin-header h1 {
        color: #2563eb;
        font-size: 2em;
        margin: 0;
    }

    .admin-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 1em;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
    }

    .btn-primary:hover {
        background: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card.green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .stat-card.blue {
        background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    }

    .stat-card.orange {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-number {
        font-size: 2.5em;
        font-weight: 700;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 0.95em;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sections-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        border-color: #2563eb;
    }

    .section-icon {
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5em;
        color: white;
    }

    .section-card.green .section-icon {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .section-card.blue .section-icon {
        background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    }

    .section-card.orange .section-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .section-content {
        padding: 25px;
    }

    .section-title {
        font-size: 1.3em;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 15px;
    }

    .section-description {
        color: #64748b;
        margin-bottom: 20px;
        font-size: 0.95em;
        line-height: 1.5;
    }

    .section-actions {
        display: flex;
        gap: 10px;
    }

    .section-actions a {
        flex: 1;
        padding: 10px 15px;
        border-radius: 6px;
        text-align: center;
        font-size: 0.9em;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        color: #2563eb;
    }

    .section-actions a:hover {
        background: #eff6ff;
        border-color: #2563eb;
    }

    .profile-preview {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-top: 40px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .profile-preview h2 {
        color: #2563eb;
        margin-top: 0;
        margin-bottom: 20px;
    }

    .profile-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .info-item {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #2563eb;
    }

    .info-label {
        font-size: 0.85em;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .info-value {
        font-size: 1.1em;
        color: #1e293b;
        word-break: break-word;
    }

    .empty-message {
        background: #eff6ff;
        border: 2px dashed #2563eb;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        color: #2563eb;
    }

    @media (max-width: 768px) {
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .admin-actions {
            width: 100%;
        }

        .admin-actions a {
            flex: 1;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .sections-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div>
            <h1>📊 CV Admin Dashboard</h1>
            <p style="color: #64748b; margin: 5px 0 0;">Manage your professional profile</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.cv.edit-profile') }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
            <a href="{{ route('cv.show') }}" class="btn btn-secondary" target="_blank">
                <i class="fas fa-eye"></i> View CV
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-briefcase" style="font-size: 2em;"></i>
            <div class="stat-number">{{ $stats['experiences'] }}</div>
            <div class="stat-label">Experiences</div>
        </div>
        <div class="stat-card green">
            <i class="fas fa-graduation-cap" style="font-size: 2em;"></i>
            <div class="stat-number">{{ $stats['educations'] }}</div>
            <div class="stat-label">Educations</div>
        </div>
        <div class="stat-card blue">
            <i class="fas fa-star" style="font-size: 2em;"></i>
            <div class="stat-number">{{ $stats['skills'] }}</div>
            <div class="stat-label">Skills</div>
        </div>
        <div class="stat-card orange">
            <i class="fas fa-code" style="font-size: 2em;"></i>
            <div class="stat-number">{{ $stats['projects'] }}</div>
            <div class="stat-label">Projects</div>
        </div>
    </div>

    <!-- Sections Grid -->
    <div class="sections-grid">
        <!-- Profile -->
        <div class="section-card">
            <div class="section-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="section-content">
                <h3 class="section-title">Profile Information</h3>
                <p class="section-description">Update your personal and professional information</p>
                <div class="section-actions">
                    <a href="{{ route('admin.cv.edit-profile') }}">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Experiences -->
        <div class="section-card green">
            <div class="section-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="section-content">
                <h3 class="section-title">Professional Experience</h3>
                <p class="section-description">
                    @if($stats['experiences'] > 0)
                        {{ $stats['experiences'] }} experience(s) added
                    @else
                        No experiences added yet
                    @endif
                </p>
                <div class="section-actions">
                    <a href="{{ route('admin.cv.experiences') }}">Manage</a>
                    <a href="{{ route('admin.cv.experience-create') }}">+ Add</a>
                </div>
            </div>
        </div>

        <!-- Educations -->
        <div class="section-card blue">
            <div class="section-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="section-content">
                <h3 class="section-title">Education</h3>
                <p class="section-description">
                    @if($stats['educations'] > 0)
                        {{ $stats['educations'] }} education(s) added
                    @else
                        No educations added yet
                    @endif
                </p>
                <div class="section-actions">
                    <a href="{{ route('admin.cv.educations') }}">Manage</a>
                    <a href="{{ route('admin.cv.education-create') }}">+ Add</a>
                </div>
            </div>
        </div>

        <!-- Skills -->
        <div class="section-card orange">
            <div class="section-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="section-content">
                <h3 class="section-title">Skills & Expertise</h3>
                <p class="section-description">
                    @if($stats['skills'] > 0)
                        {{ $stats['skills'] }} skill(s) added
                    @else
                        No skills added yet
                    @endif
                </p>
                <div class="section-actions">
                    <a href="{{ route('admin.cv.skills') }}">Manage</a>
                    <a href="{{ route('admin.cv.skill-create') }}">+ Add</a>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div class="section-card">
            <div class="section-icon">
                <i class="fas fa-code"></i>
            </div>
            <div class="section-content">
                <h3 class="section-title">Featured Projects</h3>
                <p class="section-description">
                    @if($stats['projects'] > 0)
                        {{ $stats['projects'] }} project(s) added
                    @else
                        No projects added yet
                    @endif
                </p>
                <div class="section-actions">
                    <a href="{{ route('admin.cv.projects') }}">Manage</a>
                    <a href="{{ route('admin.cv.project-create') }}">+ Add</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Preview -->
    @if($cv && $cv->id)
        <div class="profile-preview">
            <h2>📋 Profile Overview</h2>
            @if($cv->full_name)
                <div class="profile-info">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $cv->full_name }}</div>
                    </div>
                    @if($cv->title)
                        <div class="info-item">
                            <div class="info-label">Professional Title</div>
                            <div class="info-value">{{ $cv->title }}</div>
                        </div>
                    @endif
                    @if($cv->email)
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $cv->email }}</div>
                        </div>
                    @endif
                    @if($cv->phone)
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $cv->phone }}</div>
                        </div>
                    @endif
                    @if($cv->location)
                        <div class="info-item">
                            <div class="info-label">Location</div>
                            <div class="info-value">{{ $cv->location }}</div>
                        </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if($cv->is_public)
                                <span style="color: #22c55e;">🟢 Public</span>
                            @else
                                <span style="color: #ef4444;">🔴 Private</span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-message">
                    <i class="fas fa-info-circle"></i> Please edit your profile to get started
                </div>
            @endif
        </div>
    @endif
</div>

<script>
    // Add some interactivity
    document.querySelectorAll('.section-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
    });
</script>
@endsection
