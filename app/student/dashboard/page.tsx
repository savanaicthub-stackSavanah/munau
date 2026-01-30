'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
} from 'recharts';
import {
  LogOut,
  GraduationCap,
  BookOpen,
  CreditCard,
  Bell,
  Settings,
  Home,
  User,
  Calendar,
  FileText,
  Home as HomeIcon,
  Menu,
  X,
} from 'lucide-react';

const gradesData = [
  { name: 'Anatomy', grade: 85 },
  { name: 'Physiology', grade: 78 },
  { name: 'Biochemistry', grade: 92 },
  { name: 'Pharmacology', grade: 88 },
];

export default function StudentDashboard() {
  const router = useRouter();
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [userEmail, setUserEmail] = useState('');

  useEffect(() => {
    if (typeof window !== 'undefined') {
      setUserEmail(localStorage.getItem('userEmail') || '');
    }
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('authToken');
    localStorage.removeItem('userEmail');
    router.push('/');
  };

  const menuItems = [
    { icon: HomeIcon, label: 'Dashboard', href: '/student/dashboard', active: true },
    { icon: User, label: 'Profile', href: '/student/profile' },
    { icon: BookOpen, label: 'Courses', href: '/student/courses' },
    { icon: Calendar, label: 'Timetable', href: '/student/timetable' },
    { icon: FileText, label: 'Results', href: '/student/results' },
    { icon: CreditCard, label: 'Fees', href: '/student/fees' },
    { icon: Home, label: 'Hostel', href: '/student/hostel' },
  ];

  return (
    <div className="min-h-screen bg-background">
      {/* Header */}
      <header className="bg-white border-b border-border sticky top-0 z-40">
        <div className="flex items-center justify-between h-16 px-6">
          <div className="flex items-center gap-4">
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="lg:hidden"
            >
              {sidebarOpen ? (
                <X className="w-6 h-6" />
              ) : (
                <Menu className="w-6 h-6" />
              )}
            </button>
            <Link href="/student/dashboard" className="flex items-center gap-2">
              <Image
                src="/logo.png"
                alt="Munau College Logo"
                width={40}
                height={40}
                className="rounded-lg"
              />
              <span className="font-bold text-foreground hidden sm:inline">
                Munau Portal
              </span>
            </Link>
          </div>

          <div className="flex items-center gap-4">
            <button className="p-2 hover:bg-secondary/50 rounded-lg transition">
              <Bell className="w-5 h-5 text-foreground" />
            </button>
            <button className="p-2 hover:bg-secondary/50 rounded-lg transition">
              <Settings className="w-5 h-5 text-foreground" />
            </button>
            <button
              onClick={handleLogout}
              className="p-2 hover:bg-destructive/10 rounded-lg transition text-destructive"
              title="Logout"
            >
              <LogOut className="w-5 h-5" />
            </button>
          </div>
        </div>
      </header>

      <div className="flex">
        {/* Sidebar */}
        {sidebarOpen && (
          <aside className="w-64 bg-white border-r border-border min-h-screen overflow-y-auto">
            <div className="p-6">
              <div className="mb-8">
                <p className="text-sm text-muted-foreground mb-1">Logged in as:</p>
                <p className="font-semibold text-foreground break-words">{userEmail}</p>
              </div>

              <nav className="space-y-2">
                {menuItems.map((item) => (
                  <Link
                    key={item.href}
                    href={item.href}
                    className={`flex items-center gap-3 px-4 py-3 rounded-lg transition ${
                      item.active
                        ? 'bg-primary text-primary-foreground'
                        : 'text-foreground hover:bg-secondary/50'
                    }`}
                  >
                    <item.icon className="w-5 h-5" />
                    <span>{item.label}</span>
                  </Link>
                ))}
              </nav>
            </div>
          </aside>
        )}

        {/* Main Content */}
        <main className="flex-1 p-6 max-w-7xl mx-auto w-full">
          {/* Welcome Section */}
          <div className="mb-8">
            <h1 className="text-3xl font-bold text-foreground mb-2">
              Welcome Back, Student!
            </h1>
            <p className="text-muted-foreground">
              Here's an overview of your academic progress
            </p>
          </div>

          {/* Quick Stats */}
          <div className="grid md:grid-cols-4 gap-4 mb-8">
            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">GPA</p>
                  <p className="text-3xl font-bold text-foreground">3.85</p>
                </div>
                <GraduationCap className="w-12 h-12 text-primary/20" />
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">Credits Earned</p>
                  <p className="text-3xl font-bold text-foreground">48</p>
                </div>
                <BookOpen className="w-12 h-12 text-primary/20" />
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">Outstanding Fees</p>
                  <p className="text-3xl font-bold text-destructive">₦50,000</p>
                </div>
                <CreditCard className="w-12 h-12 text-destructive/20" />
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">
                    Current Level
                  </p>
                  <p className="text-3xl font-bold text-foreground">200</p>
                </div>
                <Calendar className="w-12 h-12 text-primary/20" />
              </div>
            </Card>
          </div>

          {/* Charts and Details */}
          <div className="grid lg:grid-cols-3 gap-8">
            <Card className="lg:col-span-2 p-6">
              <h2 className="text-xl font-bold mb-6 text-foreground">
                Grade Distribution
              </h2>
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={gradesData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
                  <XAxis dataKey="name" stroke="var(--color-muted-foreground)" />
                  <YAxis stroke="var(--color-muted-foreground)" />
                  <Tooltip
                    contentStyle={{
                      backgroundColor: 'var(--color-background)',
                      border: '1px solid var(--color-border)',
                    }}
                  />
                  <Bar dataKey="grade" fill="var(--color-primary)" radius={[8, 8, 0, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </Card>

            <Card className="p-6">
              <h2 className="text-xl font-bold mb-6 text-foreground">
                Academic Progress
              </h2>
              <div className="space-y-6">
                {[
                  { label: 'Semester 1', progress: 100 },
                  { label: 'Semester 2', progress: 85 },
                  { label: 'Overall', progress: 92 },
                ].map((item) => (
                  <div key={item.label}>
                    <div className="flex justify-between mb-2">
                      <span className="text-sm font-medium text-foreground">
                        {item.label}
                      </span>
                      <span className="text-sm text-muted-foreground">
                        {item.progress}%
                      </span>
                    </div>
                    <Progress value={item.progress} className="h-2" />
                  </div>
                ))}
              </div>
            </Card>
          </div>

          {/* Quick Actions */}
          <div className="mt-8 grid md:grid-cols-2 gap-6">
            <Card className="p-6">
              <h3 className="text-lg font-bold mb-4 text-foreground">
                Upcoming Classes
              </h3>
              <div className="space-y-3">
                {[
                  'Pathophysiology - Monday 09:00 AM',
                  'Clinical Skills - Tuesday 02:00 PM',
                  'Pharmacology Lab - Thursday 10:00 AM',
                ].map((item, idx) => (
                  <p key={idx} className="text-sm text-muted-foreground">
                    {item}
                  </p>
                ))}
              </div>
              <Link href="/student/timetable">
                <Button variant="outline" className="w-full mt-4 bg-transparent">
                  View Full Timetable
                </Button>
              </Link>
            </Card>

            <Card className="p-6">
              <h3 className="text-lg font-bold mb-4 text-foreground">
                Financial Status
              </h3>
              <div className="space-y-3 mb-4">
                <div className="flex justify-between text-sm">
                  <span className="text-muted-foreground">Total Fees:</span>
                  <span className="font-semibold text-foreground">₦250,000</span>
                </div>
                <div className="flex justify-between text-sm">
                  <span className="text-muted-foreground">Paid:</span>
                  <span className="font-semibold text-foreground">₦200,000</span>
                </div>
                <div className="flex justify-between text-sm border-t border-border pt-3">
                  <span className="text-muted-foreground">Outstanding:</span>
                  <span className="font-semibold text-destructive">₦50,000</span>
                </div>
              </div>
              <Link href="/student/fees">
                <Button className="w-full">Pay Fees Now</Button>
              </Link>
            </Card>
          </div>
        </main>
      </div>
    </div>
  );
}
