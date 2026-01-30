'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, Clock, MapPin } from 'lucide-react';

export default function TimetablePage() {
  const timetable = [
    {
      day: 'Monday',
      classes: [
        {
          time: '09:00 - 10:30',
          course: 'Pathophysiology II',
          code: 'NUR201',
          venue: 'Lecture Theatre A',
          instructor: 'Dr. Aminu Hassan',
        },
        {
          time: '11:00 - 12:30',
          course: 'Professional Ethics',
          code: 'GEN101',
          venue: 'Lecture Theatre B',
          instructor: 'Dr. Ibrahim Usman',
        },
      ],
    },
    {
      day: 'Tuesday',
      classes: [
        {
          time: '08:00 - 10:00',
          course: 'Clinical Nursing II (Lab)',
          code: 'NUR203',
          venue: 'Clinical Lab 1',
          instructor: 'Dr. Fatima Ali',
        },
        {
          time: '14:00 - 15:30',
          course: 'Pharmacology II',
          code: 'NUR202',
          venue: 'Lecture Theatre C',
          instructor: 'Prof. Aisha Musa',
        },
      ],
    },
    {
      day: 'Wednesday',
      classes: [
        {
          time: '10:00 - 11:30',
          course: 'Pharmacology II Lab',
          code: 'NUR202',
          venue: 'Pharmacy Lab',
          instructor: 'Prof. Aisha Musa',
        },
      ],
    },
    {
      day: 'Thursday',
      classes: [
        {
          time: '09:00 - 10:30',
          course: 'Clinical Nursing II',
          code: 'NUR203',
          venue: 'Lecture Theatre A',
          instructor: 'Dr. Fatima Ali',
        },
        {
          time: '14:00 - 15:00',
          course: 'Pathophysiology II (Discussion)',
          code: 'NUR201',
          venue: 'Tutorial Room 2',
          instructor: 'Dr. Aminu Hassan',
        },
      ],
    },
    {
      day: 'Friday',
      classes: [
        {
          time: '10:00 - 12:00',
          course: 'Clinical Nursing II (Lab)',
          code: 'NUR203',
          venue: 'Clinical Lab 2',
          instructor: 'Dr. Fatima Ali',
        },
      ],
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
          <h1 className="text-3xl font-bold text-foreground mb-2">Class Timetable</h1>
          <p className="text-muted-foreground">
            2024/2025 Academic Session - Semester 2
          </p>
        </div>

        {/* Timetable */}
        <div className="space-y-6">
          {timetable.map((daySchedule) => (
            <Card key={daySchedule.day} className="p-6">
              <h2 className="text-xl font-bold text-foreground mb-4">
                {daySchedule.day}
              </h2>
              <div className="space-y-4">
                {daySchedule.classes.map((classItem, idx) => (
                  <div
                    key={idx}
                    className="flex items-start gap-4 p-4 rounded-lg bg-secondary/5 border border-secondary/20 hover:border-secondary/40 hover:bg-secondary/10 transition"
                  >
                    <div className="flex-shrink-0">
                      <div className="w-12 h-12 rounded-lg bg-primary text-primary-foreground flex items-center justify-center">
                        <Clock className="w-6 h-6" />
                      </div>
                    </div>
                    <div className="flex-1 min-w-0">
                      <h3 className="font-bold text-foreground mb-1">
                        {classItem.course}
                      </h3>
                      <div className="space-y-2 text-sm text-muted-foreground">
                        <div className="flex items-center gap-2">
                          <Clock className="w-4 h-4" />
                          <span>{classItem.time}</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <MapPin className="w-4 h-4" />
                          <span>{classItem.venue}</span>
                        </div>
                        <p>
                          <span className="text-foreground font-medium">
                            {classItem.code}
                          </span>{' '}
                          · {classItem.instructor}
                        </p>
                      </div>
                    </div>
                    <Badge variant="outline">{classItem.code}</Badge>
                  </div>
                ))}
              </div>
            </Card>
          ))}
        </div>

        {/* Download */}
        <div className="mt-8 flex gap-4 justify-center">
          <Button variant="outline" size="lg">
            Download PDF
          </Button>
          <Button size="lg">Print Timetable</Button>
        </div>
      </div>
    </div>
  );
}
