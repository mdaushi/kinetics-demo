import { Head } from '@inertiajs/react';
import {Table} from "@mdaushi/kinetics-react"

export default function Welcome() {

    return (
        <>
            <Head title="Welcome" />
            <div className="container mx-auto mt-20">
               <Table table='posts'/>
            </div>
        </>
    );
}
