'use client';

import { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
  LineChart,
  Line,
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
} from 'recharts';
import {
  LogOut,
  GraduationCap,
  Users,
  FileText,
  CreditCard,
  TrendingUp,
  Menu,
  X,
} from 'lucide-react';

const studentData = [
  { month: 'Jan', students: 150 },
  { month: 'Feb', students: 165 },
  { month: 'Mar', students: 180 },
  { month: 'Apr', students: 195 },
  { month: 'May', students: 210 },
  { month: 'Jun', students: 225 },
];

const admissionData = [
  { name: 'Approved', value: 120, color: '#10b981' },
  { name: 'Pending', value: 45, color: '#f59e0b' },
  { name: 'Rejected', value: 15, color: '#ef4444' },
];

const recentApplications = [
  { id: 1, name: 'Alice Johnson', program: 'Nursing', status: 'pending' },
  { id: 2, name: 'Bob Smith', program: 'Medical Lab Science', status: 'approved' },
  { id: 3, name: 'Carol White', program: 'Health Records', status: 'pending' },
  { id: 4, name: 'David Brown', program: 'Nursing', status: 'rejected' },
];

export default function AdminDashboard() {
  const router = useRouter();
  const [sidebarOpen, setSidebarOpen] = useState(true);

  const handleLogout = () => {
    localStorage.removeItem('authToken');
    localStorage.removeItem('userEmail');
    router.push('/');
  };

  const menuItems = [
    { icon: GraduationCap, label: 'Dashboard', href: '/admin/dashboard' },
    { icon: Users, label: 'Students', href: '/admin/students' },
    { icon: FileText, label: 'Admissions', href: '/admin/admissions' },
    { icon: CreditCard, label: 'Finance', href: '/admin/finance' },
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
            <Link href="/admin/dashboard" className="flex items-center gap-2">
              <Image
                src="/logo.png"
                alt="Munau College Logo"
                width={40}
                height={40}
                className="rounded-lg"
              />
              <span className="font-bold text-foreground hidden sm:inline">
                Admin Panel
              </span>
            </Link>
          </div>

          <div className="flex items-center gap-4">
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
              <h3 className="text-sm font-semibold text-muted-foreground mb-4 uppercase">
                Management
              </h3>

              <nav className="space-y-2">
                {menuItems.map((item) => (
                  <Link
                    key={item.href}
                    href={item.href}
                    className={`flex items-center gap-3 px-4 py-3 rounded-lg transition ${
                      item.label === 'Dashboard'
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
              Administration Dashboard
            </h1>
            <p className="text-muted-foreground">
              System overview and management tools
            </p>
          </div>

          {/* Quick Stats */}
          <div className="grid md:grid-cols-4 gap-4 mb-8">
            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">Total Students</p>
                  <p className="text-3xl font-bold text-foreground">225</p>
                  <p className="text-xs text-green-600 mt-2">+15 this month</p>
                </div>
                <Users className="w-12 h-12 text-primary/20" />
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">
                    Pending Applications
                  </p>
                  <p className="text-3xl font-bold text-foreground">45</p>
                  <p className="text-xs text-orange-600 mt-2">Need review</p>
                </div>
                <FileText className="w-12 h-12 text-primary/20" />
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">
                    Outstanding Fees
                  </p>
                  <p className="text-3xl font-bold text-destructive">
                    ₦2.5M
                  </p>
                  <p className="text-xs text-red-600 mt-2">Action required</p>
                </div>
                <CreditCard className="w-12 h-12 text-destructive/20" />
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground mb-1">
                    Collection Rate
                  </p>
                  <p className="text-3xl font-bold text-green-600">89%</p>
                  <p className="text-xs text-green-600 mt-2">Excellent</p>
                </div>
                <TrendingUp className="w-12 h-12 text-green-600/20" />
              </div>
            </Card>
          </div>

          {/* Charts */}
          <div className="grid lg:grid-cols-3 gap-8 mb-8">
            {/* Student Growth */}
            <Card className="lg:col-span-2 p-6">
              <h2 className="text-xl font-bold mb-6 text-foreground">
                Student Enrollment Trend
              </h2>
              <ResponsiveContainer width="100%" height={300}>
                <LineChart data={studentData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
                  <XAxis dataKey="month" stroke="var(--color-muted-foreground)" />
                  <YAxis stroke="var(--color-muted-foreground)" />
                  <Tooltip
                    contentStyle={{
                      backgroundColor: 'var(--color-background)',
                      border: '1px solid var(--color-border)',
                    }}
                  />
                  <Line
                    type="monotone"
                    dataKey="students"
                    stroke="var(--color-primary)"
                    strokeWidth={2}
                    dot={{ fill: 'var(--color-primary)' }}
                  />
                </LineChart>
              </ResponsiveContainer>
            </Card>

            {/* Admission Status */}
            <Card className="p-6">
              <h2 className="text-xl font-bold mb-6 text-foreground">
                Admission Status
              </h2>
              <ResponsiveContainer width="100%" height={300}>
                <PieChart>
                  <Pie
                    data={admissionData}
                    cx="50%"
                    cy="50%"
                    labelLine={false}
                    label={({ name, value }) => `${name} ${value}`}
                    outerRadius={80}
                    fill="#8884d8"
                    dataKey="value"
                  >
                    {admissionData.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={entry.color} />
                    ))}
                  </Pie>
                  <Tooltip />
                </PieChart>
              </ResponsiveContainer>
            </Card>
          </div>

          {/* Recent Applications */}
          <Card className="p-6">
            <div className="flex justify-between items-center mb-6">
              <h2 className="text-xl font-bold text-foreground">
                Recent Applications
              </h2>
              <Link href="/admin/admissions">
                <Button variant="outline" size="sm">
                  View All
                </Button>
              </Link>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full">
                <thead>
                  <tr className="border-b border-border">
                    <th className="text-left py-3 px-4 text-foreground font-semibold">
                      Applicant Name
                    </th>
                    <th className="text-left py-3 px-4 text-foreground font-semibold">
                      Program
                    </th>
                    <th className="text-center py-3 px-4 text-foreground font-semibold">
                      Status
                    </th>
                    <th className="text-right py-3 px-4 text-foreground font-semibold">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  {recentApplications.map((app) => (
                    <tr key={app.id} className="border-b border-border hover:bg-secondary/50">
                      <td className="py-4 px-4 text-foreground">{app.name}</td>
                      <td className="py-4 px-4 text-muted-foreground">
                        {app.program}
                      </td>
                      <td className="py-4 px-4 text-center">
                        <Badge
                          variant={
                            app.status === 'approved'
                              ? 'default'
                              : app.status === 'pending'
                                ? 'secondary'
                                : 'destructive'
                          }
                        >
                          {app.status.charAt(0).toUpperCase() +
                            app.status.slice(1)}
                        </Badge>
                      </td>
                      <td className="py-4 px-4 text-right">
                        <Button variant="ghost" size="sm">
                          Review
                        </Button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Card>
        </main>
      </div>
    </div>
  );
}
