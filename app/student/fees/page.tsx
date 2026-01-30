'use client';

import { useState, useMemo } from 'react';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Download, CreditCard, CheckCircle, AlertCircle } from 'lucide-react';

export default function FeesPage() {
  const [payments, setPayments] = useState([
    {
      id: 1,
      description: 'Tuition Fee - 2024/2025 Session',
      amount: 200000,
      status: 'paid',
      date: '2024-01-15',
    },
    {
      id: 2,
      description: 'Development Fee',
      amount: 30000,
      status: 'pending',
      dueDate: '2024-02-28',
    },
    {
      id: 3,
      description: 'Registration Fee',
      amount: 5000,
      status: 'paid',
      date: '2024-01-10',
    },
    {
      id: 4,
      description: 'Library & ID Card Fee',
      amount: 10000,
      status: 'overdue',
      dueDate: '2024-01-31',
    },
  ]);

  const totalAll = useMemo(() => payments.reduce((s, p) => s + p.amount, 0), [payments]);
  const totalPaid = useMemo(() => payments.filter((p) => p.status === 'paid').reduce((s, p) => s + p.amount, 0), [payments]);
  const totalOutstanding = useMemo(() => payments.filter((p) => p.status !== 'paid').reduce((s, p) => s + p.amount, 0), [payments]);
  const totalOverdue = useMemo(() => payments.filter((p) => p.status === 'overdue').reduce((s, p) => s + p.amount, 0), [payments]);

  const [showPayModal, setShowPayModal] = useState(false);
  const [payLoading, setPayLoading] = useState(false);
  const [payAmount, setPayAmount] = useState<number>(0);
  const [payDescription, setPayDescription] = useState('');
  const [selectedPaymentId, setSelectedPaymentId] = useState<number | null>(null);
  const [paymentMethod, setPaymentMethod] = useState('Online Payment Gateway');

  const openPayModal = (amount = 0, description = '', id: number | null = null) => {
    setPayAmount(amount);
    setPayDescription(description);
    setSelectedPaymentId(id);
    setPaymentMethod('Online Payment Gateway');
    setShowPayModal(true);
  };

  const handleMockPay = async () => {
    if (!payAmount || payAmount <= 0) {
      alert('Please enter a valid amount');
      return;
    }
    setPayLoading(true);
    await new Promise((r) => setTimeout(r, 1500));
    const today = new Date().toISOString().slice(0, 10);

    if (selectedPaymentId) {
      setPayments((prev) => prev.map((p) => (p.id === selectedPaymentId ? { ...p, status: 'paid', date: today } : p)));
    } else {
      setPayments((prev) => [
        ...prev,
        {
          id: Date.now(),
          description: payDescription || 'Online Payment',
          amount: Number(payAmount),
          status: 'paid',
          date: today,
        },
      ]);
    }

    setPayLoading(false);
    setShowPayModal(false);
    alert('Payment successful (mock).');
  };

  const formatCurrency = (n: number) => `₦${n.toLocaleString()}`;

  const generateTransactionId = () => {
    return `TX-${Date.now().toString(36)}-${Math.floor(Math.random() * 10000)}`;
  };

  const downloadReceipt = (payment: any) => {
    const transactionId = generateTransactionId();
    const today = new Date().toISOString().slice(0, 10);
    const payer = typeof window !== 'undefined' ? localStorage.getItem('studentName') || localStorage.getItem('userEmail') || 'Student' : 'Student';
    const logoUrl = typeof window !== 'undefined' ? `${window.location.origin}/logo.png` : '/logo.png';

    const receipt = {
      transactionId,
      date: payment.date || today,
      description: payment.description,
      amount: payment.amount,
      paidBy: payer,
      method: payment.method || 'Online Payment Gateway'
    };

    const html = `<!doctype html>
    <html>
      <head>
        <meta charset="utf-8" />
        <title>Receipt ${transactionId}</title>
        <style>
          body { font-family: Arial, Helvetica, sans-serif; padding: 20px; color: #102a43 }
          .receipt { max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; padding: 20px }
          .header { display:flex; gap:12px; align-items:center }
          .logo { width:72px; height:72px; object-fit:contain }
          h2{ margin:0 0 6px 0 }
          table{ width:100%; border-collapse:collapse; margin-top:12px }
          td{ padding:8px 0 }
          .total{ font-weight:700; font-size:1.1rem }
          .muted{ color:#6b7280 }
        </style>
      </head>
      <body>
        <div class="receipt">
          <div class="header">
            <img src="${logoUrl}" alt="Logo" class="logo" />
            <div>
              <h2>Munau College</h2>
              <div class="muted">Official Payment Receipt</div>
            </div>
          </div>

          <div style="margin-top:16px">
            <div><strong>Transaction ID:</strong> ${receipt.transactionId}</div>
            <div><strong>Date:</strong> ${receipt.date}</div>
            <div><strong>Payer:</strong> ${receipt.paidBy}</div>
            <div><strong>Method:</strong> ${receipt.method}</div>
          </div>

          <table>
            <tr>
              <td>${receipt.description}</td>
              <td style="text-align:right">${formatCurrency(receipt.amount)}</td>
            </tr>
            <tr>
              <td class="total">Amount Paid</td>
              <td class="total" style="text-align:right">${formatCurrency(receipt.amount)}</td>
            </tr>
          </table>

          <p class="muted" style="margin-top:18px">This is a system generated receipt for demonstration purposes.</p>
        </div>
      </body>
    </html>`;

    const blob = new Blob([html], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `receipt-${transactionId}.html`;
    document.body.appendChild(a);
    a.click();
    setTimeout(() => {
      URL.revokeObjectURL(url);
      a.remove();
    }, 1000);
  };

  const downloadSummary = (allPayments: any[]) => {
    const transactionId = generateTransactionId();
    const logoUrl = typeof window !== 'undefined' ? `${window.location.origin}/logo.png` : '/logo.png';
    const payer = typeof window !== 'undefined' ? localStorage.getItem('studentName') || localStorage.getItem('userEmail') || 'Student' : 'Student';

    const rows = allPayments.map(p => `
      <tr>
        <td>${p.description}</td>
        <td style="text-align:right">${formatCurrency(p.amount)}</td>
        <td style="text-align:center">${p.status}</td>
        <td style="text-align:right">${p.date || p.dueDate || ''}</td>
      </tr>
    `).join('');

    const total = allPayments.reduce((s, p) => s + p.amount, 0);

    const html = `<!doctype html>
    <html>
      <head>
        <meta charset="utf-8" />
        <title>Payment Summary ${transactionId}</title>
        <style>
          body { font-family: Arial, Helvetica, sans-serif; padding: 20px; color: #102a43 }
          .receipt { max-width: 800px; margin: 0 auto; border: 1px solid #e2e8f0; padding: 20px }
          .logo { width:72px; height:72px; object-fit:contain }
          table{ width:100%; border-collapse:collapse; margin-top:12px }
          th, td{ padding:8px 0; border-bottom:1px solid #eef2f7 }
          .total{ font-weight:700; font-size:1.1rem }
        </style>
      </head>
      <body>
        <div class="receipt">
          <div style="display:flex; gap:12px; align-items:center">
            <img src="${logoUrl}" alt="Logo" class="logo" />
            <div>
              <h2>Munau College</h2>
              <div class="muted">Payment Summary</div>
            </div>
          </div>

          <p><strong>Payer:</strong> ${payer}</p>

          <table>
            <thead>
              <tr>
                <th style="text-align:left">Description</th>
                <th style="text-align:right">Amount</th>
                <th style="text-align:center">Status</th>
                <th style="text-align:right">Date</th>
              </tr>
            </thead>
            <tbody>
              ${rows}
              <tr>
                <td class="total">Total</td>
                <td class="total" style="text-align:right">${formatCurrency(total)}</td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </body>
    </html>`;

    const blob = new Blob([html], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `payment-summary-${transactionId}.html`;
    document.body.appendChild(a);
    a.click();
    setTimeout(() => {
      URL.revokeObjectURL(url);
      a.remove();
    }, 1000);
  };

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
          <h1 className="text-3xl font-bold text-foreground mb-2">Fees & Payments</h1>
          <p className="text-muted-foreground">
            View and manage your academic fees and payments
          </p>
        </div>

        {/* Summary Cards */}
        <div className="grid md:grid-cols-4 gap-4 mb-8">
          <Card className="p-6">
            <p className="text-sm text-muted-foreground mb-2">Total Due</p>
            <p className="text-2xl font-bold text-foreground">₦{totalAll.toLocaleString()}</p>
          </Card>
          <Card className="p-6">
            <p className="text-sm text-muted-foreground mb-2">Paid</p>
            <p className="text-2xl font-bold text-green-600">₦{totalPaid.toLocaleString()}</p>
          </Card>
          <Card className="p-6">
            <p className="text-sm text-muted-foreground mb-2">Outstanding</p>
            <p className="text-2xl font-bold text-destructive">₦{totalOutstanding.toLocaleString()}</p>
          </Card>
          <Card className="p-6">
            <p className="text-sm text-muted-foreground mb-2">Overdue</p>
            <p className="text-2xl font-bold text-orange-600">₦{totalOverdue.toLocaleString()}</p>
          </Card>
        </div>

        {/* Alert */}
        <Card className="p-4 mb-8 border-orange-200 bg-orange-50">
          <div className="flex gap-3">
            <AlertCircle className="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" />
            <div>
              <p className="font-semibold text-orange-900">Outstanding Balance Alert</p>
              <p className="text-sm text-orange-800 mt-1">
                You have ₦45,000 in outstanding fees. Please pay to avoid academic
                sanctions.
              </p>
            </div>
          </div>
        </Card>

        {/* Payment Table */}
        <Card className="p-6 mb-8">
          <h2 className="text-xl font-bold mb-6 text-foreground">Payment Details</h2>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-border">
                  <th className="text-left py-3 px-4 text-foreground font-semibold">
                    Description
                  </th>
                  <th className="text-right py-3 px-4 text-foreground font-semibold">
                    Amount
                  </th>
                  <th className="text-center py-3 px-4 text-foreground font-semibold">
                    Status
                  </th>
                  <th className="text-right py-3 px-4 text-foreground font-semibold">
                    Date
                  </th>
                  <th className="text-right py-3 px-4 text-foreground font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody>
                {payments.map((payment) => (
                  <tr key={payment.id} className="border-b border-border hover:bg-secondary/50">
                    <td className="py-4 px-4 text-foreground">{payment.description}</td>
                    <td className="py-4 px-4 text-right font-semibold text-foreground">
                      ₦{payment.amount.toLocaleString()}
                    </td>
                    <td className="py-4 px-4 text-center">
                      <Badge
                        variant={
                          payment.status === 'paid'
                            ? 'default'
                            : payment.status === 'pending'
                              ? 'secondary'
                              : 'destructive'
                        }
                      >
                        {payment.status.charAt(0).toUpperCase() +
                          payment.status.slice(1)}
                      </Badge>
                    </td>
                    <td className="py-4 px-4 text-right text-muted-foreground">
                      {payment.date || payment.dueDate}
                    </td>
                    <td className="py-4 px-4 text-right">
                      {payment.status !== 'paid' ? (
                        <Button size="sm" onClick={() => openPayModal(payment.amount, payment.description, payment.id)}>
                          Pay
                        </Button>
                      ) : (
                        <Button variant="outline" size="sm" className="gap-2" onClick={() => downloadReceipt(payment)}>
                          <Download className="w-4 h-4" />
                          Receipt
                        </Button>
                      )}
                    </td>
                  </tr>
                ))} 
              </tbody>
            </table>
          </div>
        </Card>

        {/* Payment Methods */}
        <Card className="p-6 mb-8">
          <h2 className="text-xl font-bold mb-6 text-foreground">Payment Methods</h2>
          <div className="grid md:grid-cols-3 gap-4">
            {[
              { name: 'Bank Transfer', icon: '🏦' },
              { name: 'Online Payment Gateway', icon: '💳' },
              { name: 'Mobile Money', icon: '📱' },
            ].map((method) => (
              <Card key={method.name} className="p-4 border border-border hover:border-primary hover:shadow-lg transition cursor-pointer">
                <p className="text-3xl mb-2">{method.icon}</p>
                <p className="font-semibold text-foreground">{method.name}</p>
              </Card>
            ))}
          </div>
        </Card>

        {/* Action Buttons */}

        {/* Mock Payment Modal */}
        {showPayModal && (
          <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <Card className="p-6 w-full max-w-md">
              <h3 className="text-lg font-semibold mb-2">Mock Payment Gateway</h3>
              <p className="text-sm text-muted-foreground mb-4">This is a simulated payment flow for demo purposes.</p>

              <div className="space-y-3">
                <div>
                  <Label htmlFor="amount">Amount</Label>
                  <Input
                    id="amount"
                    type="number"
                    value={payAmount}
                    onChange={(e) => setPayAmount(Number(e.target.value))}
                    className="mt-1"
                  />
                </div>

                <div>
                  <Label htmlFor="method">Payment Method</Label>
                  <select
                    id="method"
                    value={paymentMethod}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                    className="w-full mt-1 rounded-md border px-3 py-2"
                  >
                    <option>Online Payment Gateway</option>
                    <option>Bank Transfer</option>
                    <option>Mobile Money</option>
                  </select>
                </div>

                <div className="flex gap-2 mt-4">
                  <Button loading={payLoading} onClick={handleMockPay} className="flex-1">
                    <CreditCard className="w-4 h-4" />
                    Pay Now
                  </Button>
                  <Button variant="outline" className="flex-1" onClick={() => setShowPayModal(false)}>
                    Cancel
                  </Button>
                </div>
              </div>
            </Card>
          </div>
        )}

        <div className="flex gap-4">
          <Button className="flex-1 gap-2" size="lg" onClick={() => openPayModal(totalOutstanding, 'Outstanding Fees')}>
            <CreditCard className="w-5 h-5" />
            Pay Outstanding Fees
          </Button>
          <Button variant="outline" className="flex-1 gap-2 bg-transparent" size="lg" onClick={() => downloadSummary(payments)}>
            <Download className="w-5 h-5" />
            Download Receipt
          </Button>
        </div>
      </div>
    </div>
  );
}
