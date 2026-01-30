'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, BookOpen } from 'lucide-react';

export default function CoursesPage() {
  const courses = [
    {
      code: 'NUR201',
      title: 'Pathophysiology II',
      credits: 3,
      instructor: 'Dr. Aminu Hassan',
      status: 'enrolled',
    },
    {
      code: 'NUR202',
      title: 'Pharmacology II',
      credits: 4,
      instructor: 'Prof. Aisha Musa',
      status: 'enrolled',
    },
    {
      code: 'NUR203',
      title: 'Clinical Nursing II',
      credits: 5,
      instructor: 'Dr. Fatima Ali',
      status: 'enrolled',
    },
    {
      code: 'GEN101',
      title: 'Professional Ethics in Healthcare',
      credits: 2,
      instructor: 'Dr. Ibrahim Usman',
      status: 'available',
    },
  ];

  return (
    <div className="min-h-screen bg-background p-6">
      <div className="max-w-5xl mx-auto">
        {/* Header */}
        <div className="mb-8">
          <Link href="/student/dashboard">
            <Button variant="ghost" className="gap-2 mb-4">
              <ArrowLeft className="w-4 h-4" />
              Back to Dashboard
            </Button>
          </Link>
          <h1 className="text-3xl font-bold text-foreground mb-2">My Courses</h1>
          <p className="text-muted-foreground">
            View and register for your courses this semester
          </p>
        </div>

        {/* Summary */}
        <Card className="p-6 mb-8">
          <div className="grid md:grid-cols-3 gap-6">
            <div>
              <p className="text-sm text-muted-foreground mb-1">Enrolled Courses</p>
              <p className="text-3xl font-bold text-foreground">3</p>
            </div>
            <div>
              <p className="text-sm text-muted-foreground mb-1">Total Credits</p>
              <p className="text-3xl font-bold text-foreground">12</p>
            </div>
            <div>
              <p className="text-sm text-muted-foreground mb-1">
                Available to Register
              </p>
              <p className="text-3xl font-bold text-foreground">1</p>
            </div>
          </div>
        </Card>

        {/* Courses List */}
        <div className="grid gap-4">
          {courses.map((course) => (
            <Card key={course.code} className="p-6 hover:shadow-lg transition">
              <div className="flex items-start justify-between">
                <div className="flex gap-4 flex-1">
                  <div className="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <BookOpen className="w-6 h-6 text-primary" />
                  </div>
                  <div className="flex-1">
                    <h3 className="text-lg font-bold text-foreground mb-1">
                      {course.code} - {course.title}
                    </h3>
                    <p className="text-sm text-muted-foreground mb-3">
                      Instructor: {course.instructor}
                    </p>
                    <p className="text-sm text-muted-foreground">
                      Credits: {course.credits}
                    </p>
                  </div>
                </div>
                <div className="text-right">
                  <Badge
                    variant={
                      course.status === 'enrolled' ? 'default' : 'secondary'
                    }
                    className="mb-3"
                  >
                    {course.status === 'enrolled' ? 'Enrolled' : 'Available'}
                  </Badge>
                  {course.status === 'available' && (
                    <Button size="sm" className="block w-full">
                      Register
                    </Button>
                  )}
                </div>
              </div>
            </Card>
          ))}
        </div>
      </div>
    </div>
  );
}
