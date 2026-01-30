<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Department;
use App\Models\News;
use App\Models\Event;
use App\Models\CollegeInfo;
use App\Models\ManagementStaff;
use App\Models\GoverningCouncil;
use App\Models\GalleryAlbum;
use App\Models\Download;

class WebController extends Controller
{
    public function home()
    {
        $featuredPrograms = Program::where('is_active', true)
            ->limit(3)
            ->get();

        $latestNews = News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        $upcomingEvents = Event::where('status', 'scheduled')
            ->where('event_date_start', '>=', now())
            ->orderBy('event_date_start', 'asc')
            ->limit(3)
            ->get();

        return view('web.home', compact('featuredPrograms', 'latestNews', 'upcomingEvents'));
    }

    public function about()
    {
        return view('web.about');
    }

    public function programs()
    {
        $programs = Program::where('is_active', true)
            ->with('department')
            ->paginate(12);

        return view('web.programs', compact('programs'));
    }

    public function programDetail($id)
    {
        $program = Program::findOrFail($id);
        $relatedPrograms = Program::where('department_id', $program->department_id)
            ->where('id', '!=', $id)
            ->limit(3)
            ->get();

        return view('web.program-detail', compact('program', 'relatedPrograms'));
    }

    public function departments()
    {
        $departments = Department::where('is_active', true)
            ->with('programs')
            ->paginate(6);

        return view('web.departments', compact('departments'));
    }

    public function departmentDetail($id)
    {
        $department = Department::with('programs', 'headOfDepartment')
            ->findOrFail($id);

        return view('web.department-detail', compact('department'));
    }

    public function admissionRequirements()
    {
        $programs = Program::where('is_active', true)->get();
        return view('web.admission-requirements', compact('programs'));
    }

    public function news()
    {
        $articles = News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('web.news', compact('articles'));
    }

    public function newsDetail($slug)
    {
        $article = News::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $article->increment('views');

        $relatedArticles = News::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->limit(3)
            ->get();

        return view('web.news-detail', compact('article', 'relatedArticles'));
    }

    public function events()
    {
        $events = Event::where('status', '!=', 'cancelled')
            ->orderBy('event_date_start', 'desc')
            ->paginate(6);

        return view('web.events', compact('events'));
    }

    public function eventDetail($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('web.event-detail', compact('event'));
    }

    public function gallery()
    {
        $albums = GalleryAlbum::where('status', 'published')
            ->with('images')
            ->paginate(6);

        return view('web.gallery', compact('albums'));
    }

    public function galleryAlbum($slug)
    {
        $album = GalleryAlbum::where('slug', $slug)
            ->where('status', 'published')
            ->with('images')
            ->firstOrFail();

        return view('web.gallery-album', compact('album'));
    }

    public function management()
    {
        $management = ManagementStaff::where('is_visible', true)
            ->orderBy('order', 'asc')
            ->get();

        $council = GoverningCouncil::where('is_visible', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('web.management', compact('management', 'council'));
    }

    public function downloads()
    {
        $downloads = Download::where('status', 'active')
            ->where('access_level', 'public')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('web.downloads', compact('downloads'));
    }

    public function contact()
    {
        return view('web.contact');
    }

    public function contactSubmit()
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Log contact message or send email
        // ContactMessage::create($validated);
        // Or: Mail::send('emails.contact', $validated, function($m) { ... });

        return back()->with('success', 'Thank you for your message. We will contact you soon.');
    }

    public function search()
    {
        $query = request()->input('q');

        if (strlen($query) < 3) {
            return redirect()->back()->with('error', 'Search query must be at least 3 characters long.');
        }

        $programs = Program::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();

        $news = News::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->get();

        return view('web.search-results', compact('programs', 'news', 'query'));
    }
}
