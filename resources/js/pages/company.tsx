import { useState, useEffect } from 'react';
import axios from 'axios';
// import { Head, usePage, router, Link, route } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
// import { PencilIcon, Ellipsis } from "lucide-react";
import {
    Sheet,
    // SheetClose,
    SheetContent,
    // SheetDescription,
    // SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/components/ui/sheet";
// import { Button } from '@/components/ui/button';
// import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company',
        href: '/company',
    },
];

interface TeamMemberData {
    id: number;
    image: string | null;
    name: string;
    job_title: string;
}

interface Member {
    id: number;
    name: string;
    full_name: string;
    email: string;
    phone: string;
    job_title: string;
    image: string | null;
    address: string;
    access: string;
}

export default function Company() {
    const { team } = usePage<{ team: TeamMemberData[] }>().props;

    const [selectedUser, setSelectedUser] = useState<Member | null>(null);

    const [openSheet, setOpenSheet] = useState(false);

    const [loading, setLoading] = useState(false);



    const fetchUserDetail = async (id: number) => {
        setLoading(true);
        window.history.pushState(null, '', window.location.href);
        try {
            const response = await axios.get(`/users/${id}`);
            // console.log("Data user:", response.data);
            setSelectedUser(response.data);
        } catch (error) {
            console.error("Gagal mengambil data user:", error);
            setSelectedUser(null);
        } finally {
            setLoading(false);
        }
    };
    const TeamMember = ({ id, image, name, job_title }: TeamMemberData) => {

        useEffect(() => {
            const handlePopState = () => {
                if (openSheet) {
                    setOpenSheet(false);
                }
            };

            window.addEventListener('popstate', handlePopState);

            return () => {
                window.removeEventListener('popstate', handlePopState);
            };
        }, [openSheet]);

        return (
            <SheetTrigger asChild>
                <Card
                    className="w-54 md:w-64 flex-shrink-0 cursor-pointer"
                    onClick={() => {
                        fetchUserDetail(id);
                        setOpenSheet(true);
                    }}
                >
                    <CardHeader className="flex flex-col items-center">
                        <Avatar className="mb-4 w-28 h-28 md:w-32 md:h-32 overflow-hidden">
                            <AvatarImage src={image || ''} className="object-cover w-full h-full rounded-full" />
                            <AvatarFallback>CN</AvatarFallback>
                        </Avatar>
                    </CardHeader>
                    <CardContent>
                        <CardTitle className='text-2xl md:text-xl mb-2 text-center'>{name}</CardTitle>
                        <CardDescription className="text-l md:text-lg text-center">{job_title}</CardDescription>
                    </CardContent>
                </Card>
            </SheetTrigger>
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Company" />
            <Sheet open={openSheet} onOpenChange={setOpenSheet}>
                <section className="mb-15 h-38" style={{ backgroundImage: `url('https://picsum.photos/seed/${Math.random()}/1200/400')` }}>
                    <div id='profile' className="relative z-10 flex items-center ml-5 mt-22">
                        <img src="/logoSkak.png" alt="Logo" className="h-30 w-30 border-3 border-white rounded-xl shadow-lg" />
                        <div className="ml-4 mt-20" style={{ paddingRight: '20px' }}>
                            <h1 className="text-2xl font-bold mb-0.5">SKAK STUDIOS</h1>
                            <p className="text-l">IP Development & Production Company</p>
                        </div>
                    </div>
                </section>

                <hr className='mt-15 md:mt-10' />

                <section className='p-6 rounded-lg'>
                    <h2 className="text-xl font-bold mb-2">Vision</h2>
                    <p className="mb-4">Most Valuable IP Company di Indonesia.</p>
                    <h2 className="text-xl font-bold mb-2">Mission</h2>
                    <p className="mb-4">
                        Menjadi yang terdepan di industri IP dengan semangat "Lokal menuju Global".
                        Melakukan inovasi dan kolaborasi dengan partner serta talenta terbaik untuk menghasilkan produk terbaik.
                        Memberikan manfaat dan keuntungan sebesar-besarnya kepada stakeholder.
                    </p>
                    <h2 className="text-xl font-bold mb-2">Value</h2>
                    <p className="mb-4">
                        Memberikan kegembiraan, inspirasi, dan semangat positif kepada masyarakat melalui tontonan serta produk kreatif dan inovatif.
                    </p>
                </section>

                <section className='p-6 rounded-lg'>
                    <h2 className="text-xl font-bold mb-2">Company Structure</h2>
                    <div className="flex overflow-x-auto space-x-4 mt-4">
                        {team.map((member) => (
                            <TeamMember
                                key={member.id}
                                id={member.id}
                                image={member.image}
                                name={member.name}
                                job_title={member.job_title}
                            />
                        ))}
                    </div>
                    <div className="flex justify-end mt-4">
                        <a href={route('employee')} className="text-blue-500 hover:text-blue-700">
                            See All{""}
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </section>

                <SheetContent>
                    <SheetHeader>
                        <SheetTitle className='text-center'>{loading ? 'Loading...' : selectedUser?.name || ''}</SheetTitle>
                    </SheetHeader>
                    {loading ? (
                        <p>Loading...</p>
                    ) : (
                        <div className="bg-white p-6">

                            {/* Profile Picture */}
                            <div className="flex flex-col items-center mb-4">
                                <div className="relative w-24 h-24 overflow-hidden rounded-full border-2 border-purple-500">
                                    <img
                                        src={selectedUser?.image || 'https://via.placeholder.com/150'}
                                        alt="Profile Picture"
                                        className="w-full h-full object-cover"
                                    />
                                </div>
                                <h2 className="text-2xl font-bold mt-2 text-center">{selectedUser?.name || ''}</h2>
                                <p className="text-gray-600 text-center">{selectedUser?.job_title || ''}</p>

                            </div>

                            {/* Action Buttons */}
                            {/* <div className="flex justify-center gap-2 mb-4 ">
                                <Button variant="outline">
                                    <PencilIcon className="mr-2 w-4 h-4" /> Edit
                                </Button>
                                <Button variant="outline">
                                <Ellipsis />
                                </Button>
                            </div> */}

                            {/* Details */}
                            <ul className="">
                                {/* Name */}
                                <li className="flex items-center">
                                <span className="text-gray-600 text-left">Name</span>
                                <span className="ml-auto text-gray-800 font-medium text-right">
                                    {selectedUser?.full_name || ''}
                                </span>
                                </li>

                                {/* Email */}
                                <li className="flex items-center">
                                <span className="text-gray-600 text-left">Email</span>
                                <span className="ml-auto text-purple-500 font-medium text-right">
                                    {selectedUser?.email || ''}
                                </span>
                                </li>

                                {/* Phone Number */}
                                <li className="flex items-center">
                                <span className="text-gray-600 text-left">Phone Number</span>
                                <span className="ml-auto text-gray-800 font-medium text-right">
                                    {selectedUser?.phone || ''}
                                </span>
                                </li>

                                {/* Gender */}
                                {/* <li className="flex items-center">
                                <span className="text-gray-600 text-left">Gender</span>
                                <span className="ml-auto text-gray-800 font-medium text-right">
                                    {selectedUser?.gender || 'Male'}
                                </span>
                                </li> */}

                                {/* Address */}
                                <li className="flex items-center">
                                <span className="text-gray-600 text-left">Address</span>
                                <span className=" text-gray-800 font-medium text-right">
                                    {selectedUser?.address || ''}
                                </span>
                                </li>

                                {/* Access */}
                                <li className="flex items-center">
                                    <span className="text-gray-600 text-left">Group</span>
                                    <span className="ml-auto text-gray-800 font-medium text-right">
                                        {selectedUser?.access === 'team bod' ? 'Board of Director' :
                                        selectedUser?.access === 'team' ? 'Team' :
                                        selectedUser?.access === 'intern' ? 'Intern' :
                                        'Employee'}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    )}

                </SheetContent>
            </Sheet>
        </AppLayout>
    );
}
