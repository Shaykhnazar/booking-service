export interface Service {
    id: number;
    name: string;
    description: string | null;
    duration_minutes: number;
}

export interface TimeSlot {
    start_time: string;
    start_time_iso: string;
    end_time: string;
}

export interface BookingForm {
    service_id: number;
    client_name: string;
    client_phone: string;
    start_time: string;
}

export interface PageProps {
    services?: Service[];
    flash?: {
        success?: string;
        error?: string;
    };
}
