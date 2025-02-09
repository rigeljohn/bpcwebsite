<?php


namespace App\Http\Controllers;

use App\Models\Jobs;
use App\Models\News;
use App\Models\User;
use App\Models\Events;
use App\Models\Survey;
use App\Models\Gallery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    //user pages
    public function home()
    {

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->email_verified_at) {

                if ($user->user_type == 'alumni') {
                    if ($user->add_info_completed == false) {
                        return view('auth.additional-info')->with('info', 'Please Add Your Information.');
                    } else {
                        if ($user->survey_completed == false) {
                            return view('auth.survey')->with('info', 'Please complete this survey.');
                        } else {
                            return view('auth.home');
                        }
                    }
                } else if ($user->user_type == 'admin') {
                    return view('admin.dashboard');
                }

            } else {
                return view('auth.verify-email');
            }

        } else {
            return view('index');
        }
    }
    public function addInfo()
    {
        return view('auth.additional-info');
    }
    public function survey()
    {
        return view('auth.survey');
    }
    public function news()
    {
        if (auth()->check()) {
            return view('auth.news');
        }
        return view('news');
    }

    public function events()
    {
        if (auth()->check()) {
            return view('auth.events');
        }
        return view('events');
    }
    public function jobs()
    {
        if (auth()->check()) {
            return view('auth.jobs');
        }
        return view('jobs');
    }
    public function forums()
    {
        if (auth()->check()) {
            return view('auth.forums');
        }
        return view('forums');
    }
    public function gallery()
    {
        if (auth()->check()) {
            return view('auth.gallery');
        }
        return view('gallery');
    }
    public function login()
    {
        return view('login');
    }
    public function editProfile(User $user)
    {
        return view('auth.edit-profile', ['user' => auth()->user()]);
    }



    // admin pages
    // private function getSharedData($user, $news, $events, $jobs, $gallery, $survey)
    // {
    //     View::share('sharedData', [
    //         'user' => $user,
    //         'news' => $news,
    //         'events' => $events,
    //         'jobs' => $jobs,
    //         'gallery' => $gallery,
    //     ]);
    // }

    //edit page
    public function editJobsPage(Events $jobs)
    {
        return view('admin.edit-jobs', ['jobs' => $jobs]);
    }
    public function editEventsPage(Events $events)
    {
        return view('admin.edit-events', ['events' => $events]);
    }
    public function editNewsPage(News $news)
    {
        return view('admin.edit-news', ['news' => $news]);
    }
    public function editAlumniPage(User $user)
    {
        return view('admin.edit-alumni', ['user' => $user]);
    }

    //add page
    public function addJobsPage(Events $jobs)
    {
        return view('admin.add-jobs', ['jobs' => $jobs]);
    }
    public function addEventsPage(Events $events)
    {
        return view('admin.add-events', ['events' => $events]);
    }
    public function addNewsPage(News $news)
    {
        return view('admin.add-news', ['news' => $news, 'thumbnail' => $news->thumbnail]);
    }
    public function addAlumniPage(User $user)
    {
        $user = auth()->user();
        return view('admin.add-alumni', ['user' => $user]);
    }
    public function addSurveyPage()
    {
        return view('admin.add-survey');
    }

    //
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
    public function adminUsers(User $user)
    {
        return view('admin.users', ['users' => $user->latest()->get()->where('user_type', '!=', 'admin')]);
    }
    public function adminSurvey(Survey $survey)
    {
        return view('admin.surveys', ['surveys' => $survey->surveyQuestions()->latest()->get()]);
    }
    public function adminNews(News $news)
    {
        return view('admin.news', ['news' => $news->latest()->get()]);
    }
    public function adminEvents(Events $events)
    {
        return view('admin.events', ['events' => $events->latest()->get()]);
    }
    public function adminGallery(Gallery $gallery)
    {
        return view('admin.gallery', ['gallery' => $gallery->latest()->get()]);
    }
    public function adminJobs(Jobs $jobs)
    {
        return view('admin.jobs', ['jobs' => $jobs->latest()->get()]);
    }
    public function adminForums(User $user)
    {
        return view('admin.forums', ['surveys' => $user->latest()->get()]);
    }
}