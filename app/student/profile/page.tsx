'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Save, Edit2 } from 'lucide-react';
import { useState } from 'react';

export default function ProfilePage() {
  const [editing, setEditing] = useState(false);
  const [profile, setProfile] = useState({
    firstName: 'John',
    lastName: 'Doe',
    email: 'john@munaucollege.edu.ng',
    studentId: 'MCH/2024/001234',
    phone: '+234 803 123 4567',
    dateOfBirth: '2000-01-15',
    program: 'Nursing Science (RN)',
    level: '200',
    admissionYear: '2024',
    batch: 'A',
    address: '123 Healthcare Avenue, Lagos',
    state: 'Lagos State',
    country: 'Nigeria',
  });

  return (
    <div className="min-h-screen bg-background p-6">
      <div className="max-w-3xl mx-auto">
        {/* Header */}
        <div className="mb-8">
          <Link href="/student/dashboard">
            <Button variant="ghost" className="gap-2 mb-4">
              <ArrowLeft className="w-4 h-4" />
              Back to Dashboard
            </Button>
          </Link>
          <div className="flex justify-between items-start">
            <div>
              <h1 className="text-3xl font-bold text-foreground mb-2">My Profile</h1>
              <p className="text-muted-foreground">
                View and manage your student information
              </p>
            </div>
            <Button
              variant={editing ? 'default' : 'outline'}
              className="gap-2"
              onClick={() => setEditing(!editing)}
            >
              {editing ? (
                <>
                  <Save className="w-4 h-4" />
                  Save Changes
                </>
              ) : (
                <>
                  <Edit2 className="w-4 h-4" />
                  Edit Profile
                </>
              )}
            </Button>
          </div>
        </div>

        {/* Student ID Card */}
        <Card className="p-8 mb-8 bg-gradient-to-br from-primary to-primary/80 text-primary-foreground">
          <div className="flex justify-between items-start mb-8">
            <div>
              <p className="text-sm opacity-90 mb-1">Student ID</p>
              <p className="text-2xl font-bold">{profile.studentId}</p>
            </div>
            <div className="text-right">
              <p className="text-sm opacity-90">Admission Year</p>
              <p className="text-2xl font-bold">{profile.admissionYear}</p>
            </div>
          </div>
          <div>
            <p className="text-xl font-semibold">
              {profile.firstName} {profile.lastName}
            </p>
            <p className="opacity-90">{profile.program}</p>
          </div>
        </Card>

        {/* Personal Information */}
        <Card className="p-8 mb-8">
          <h2 className="text-2xl font-bold mb-6 text-foreground">
            Personal Information
          </h2>
          <div className="grid md:grid-cols-2 gap-6">
            <div className="space-y-2">
              <Label>First Name</Label>
              <Input
                value={profile.firstName}
                disabled={!editing}
                onChange={(e) =>
                  setProfile({ ...profile, firstName: e.target.value })
                }
              />
            </div>
            <div className="space-y-2">
              <Label>Last Name</Label>
              <Input
                value={profile.lastName}
                disabled={!editing}
                onChange={(e) =>
                  setProfile({ ...profile, lastName: e.target.value })
                }
              />
            </div>
            <div className="space-y-2">
              <Label>Email Address</Label>
              <Input
                value={profile.email}
                disabled={!editing}
                onChange={(e) => setProfile({ ...profile, email: e.target.value })}
              />
            </div>
            <div className="space-y-2">
              <Label>Phone Number</Label>
              <Input
                value={profile.phone}
                disabled={!editing}
                onChange={(e) => setProfile({ ...profile, phone: e.target.value })}
              />
            </div>
            <div className="space-y-2">
              <Label>Date of Birth</Label>
              <Input
                type="date"
                value={profile.dateOfBirth}
                disabled={!editing}
                onChange={(e) =>
                  setProfile({ ...profile, dateOfBirth: e.target.value })
                }
              />
            </div>
            <div className="space-y-2">
              <Label>Address</Label>
              <Input
                value={profile.address}
                disabled={!editing}
                onChange={(e) =>
                  setProfile({ ...profile, address: e.target.value })
                }
              />
            </div>
            <div className="space-y-2">
              <Label>State</Label>
              <Input
                value={profile.state}
                disabled={!editing}
                onChange={(e) => setProfile({ ...profile, state: e.target.value })}
              />
            </div>
            <div className="space-y-2">
              <Label>Country</Label>
              <Input
                value={profile.country}
                disabled={!editing}
                onChange={(e) =>
                  setProfile({ ...profile, country: e.target.value })
                }
              />
            </div>
          </div>
        </Card>

        {/* Academic Information */}
        <Card className="p-8">
          <h2 className="text-2xl font-bold mb-6 text-foreground">
            Academic Information
          </h2>
          <div className="grid md:grid-cols-2 gap-6">
            <div className="space-y-2">
              <Label>Program</Label>
              <Input value={profile.program} disabled />
            </div>
            <div className="space-y-2">
              <Label>Current Level</Label>
              <Input value={profile.level} disabled />
            </div>
            <div className="space-y-2">
              <Label>Batch</Label>
              <Input value={profile.batch} disabled />
            </div>
            <div className="space-y-2">
              <Label>Student ID</Label>
              <Input value={profile.studentId} disabled />
            </div>
          </div>
        </Card>
      </div>
    </div>
  );
}
