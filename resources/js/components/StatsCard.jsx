import React from 'react';

export default function StatsCard({ title, value, icon, color }) {
    const colorClasses = {
        green: 'bg-green-500',
        blue: 'bg-blue-500',
        purple: 'bg-purple-500',
        orange: 'bg-orange-500',
        red: 'bg-red-500',
    };

    return (
        <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between">
                <div>
                    <p className="text-sm text-gray-600 mb-1">{title}</p>
                    <p className="text-2xl font-bold text-gray-800">{value}</p>
                </div>
                <div className={`${colorClasses[color]} p-3 rounded-full text-white text-2xl`}>
                    {icon}
                </div>
            </div>
        </div>
    );
}