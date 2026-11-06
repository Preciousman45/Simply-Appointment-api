<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;
use Appointments\Middleware\rescheduleUserAppointment;
use Appointments\Middleware\CancelAppointment;
use Appointments\Middleware\ListAppointments;
use Appointments\Middleware\GetAppointment;
use Appointments\Middleware\GetUserAppointment;
use Appointments\Middleware\GetAvailableDates;
use Appointments\Middleware\GetAvailableTimes;
use Appointments\Middleware\BookAppointment;
use Appointments\Middleware\RescheduleAppointment;
use Appointments\Middleware\CancelUserAppointment;

use Appointments\Middleware\GetUserAppointments;
use service\Middleware\CreateService;
use service\Middleware\GetServices;
use service\Middleware\DeleteService;
use service\Middleware\GetService;
use service\Middleware\UpdateService;

use staffService\Middleware\AddStaffService;
use staffService\Middleware\GetStaffAppointments;
use staffService\Middleware\GetStaffService;
use staffService\Middleware\GetStaffServices;
use staffService\Middleware\RemoveService;
use staffService\Middleware\UpdateAppointmentStatus;
use user\Middleware\CreateStaff;
use user\Middleware\GetStaffs;
use user\Middleware\Login;
use Appointments\Middleware\ValidateAppointmentId;
use Appointments\Middleware\ValidateAppointmentIdAndUserId;
use service\Middleware\ValidateServiceId;
use staffService\Middleware\ValidateStaffIdAndServiceId;
use staffService\Middleware\ValidateStaffId;

use JimTools\JwtAuth\Middleware\JwtAuthentication;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {


    // USERS RESOURCE (User Module)
    $app->get(
        '/user/{userId}/appointments/{appointmentId}',
        [
            JwtAuthentication::class,
            ValidateAppointmentIdAndUserId::class,
            GetUserAppointment::class
        ],
        'get.user-appointment'
    );

    $app->delete(
        '/user/{userId}/appointments/{appointmentId}',
        [
            JwtAuthentication::class,
            ValidateAppointmentIdAndUserId::class,
            CancelUserAppointment::class
        ],
        'cancel.user-appointment'

    );
    $app->post(
        '/users/{userId}/appointments',
        [
            // JwtAuthentication::class,
            BookAppointment::class
        ],
        'book.appointment'
    );
    $app->get(
        '/users/{userId}/appointments',
        [
            JwtAuthentication::class,
            GetUserAppointments::class
        ],
        'user.appointment'
    );
    $app->patch(
        '/users/{userId}/appointments/{appointmentId}/reschedule',
        [
            JwtAuthentication::class,
            ValidateAppointmentIdAndUserId::class,
            RescheduleUserAppointment::class
        ],
        'reschedule.user-appointment'
    );

    ////staff
    $app->post(
        '/auth/login',
        Login::class,
        'user.login'
    );
    $app->get(
        '/staffs',
        [
            JwtAuthentication::class,
            GetStaffs::class
        ],
        'staff.list'
    );
    $app->post(
        '/staffs',
        [
            JwtAuthentication::class,
            CreateStaff::class
        ],
        'staff.create'
    );
    $app->post(
        '/staffs/{staffId}/services/{serviceId}',
        [
            JwtAuthentication::class,
            ValidateStaffIdAndServiceId::class,
            AddStaffService::class
        ],
        'staff-service.add'
    );
    $app->get(
        '/staffs/{staffId}/services/{serviceId}',
        [
            JwtAuthentication::class,
            ValidateStaffIdAndServiceId::class,
            GetStaffService::class
        ],
        'staf-service.get'
    );
    $app->delete(
        '/staffs/{staffId}/services/{serviceId}',
        [
            JwtAuthentication::class,
            ValidateStaffIdAndServiceId::class,
            RemoveService::class
        ],
        'staf-service.delete'
    );
    $app->get(
        '/staffs/{staffId}/services',
        [
            JwtAuthentication::class,
            ValidateStaffId::class,
            GetStaffServices::class
        ],
        'staf-services.get'
    );
    $app->get(
        '/staffs/{staffId}/appointments',
        [
            JwtAuthentication::class,
            ValidateStaffId::class,
            GetStaffAppointments::class
        ],
        'staf-appointment.get'
    );
    $app->patch(
        '/staffs/{staffId}/appointments/{appointmentId}',
        [
            JwtAuthentication::class,
            UpdateAppointmentStatus::class
        ],
        'staf-appointment.update'
    );

    ///Appointments
    $app->get(
        '/appointments',
        [
            JwtAuthentication::class,
            ListAppointments::class
        ],
        'appointments-list.get'
    );

    $app->get(
        '/appointments/available_date/{staffId}',
        [
            JwtAuthentication::class,
            GetAvailableDates::class
        ],
        'appointment-availabledates.get'
    );

    $app->get(
        '/appointments/available_time',
        [
            JwtAuthentication::class,
            GetAvailableTimes::class
        ],
        'appointment-availabletimes.get
         '
    );

    // 2. Dynamic/Wildcard routes come last

    $app->get(
        '/appointments/{appointmentId}',
        [
            JwtAuthentication::class,
            ValidateAppointmentId::class,
            GetAppointment::class
        ],
        'appointment.get'
    );

    $app->delete(
        '/appointments/{appointmentId}',
        [
            JwtAuthentication::class,
            ValidateAppointmentId::class,
            CancelAppointment::class
        ],
        'appointment.delete'
    );

    $app->patch(
        '/appointments/{appointmentId}/reschedule',
        [
            JwtAuthentication::class,
            ValidateAppointmentId::class,
            RescheduleAppointment::class
        ],
        'appointment.update'
    );



    $app->get(
        '/services',
        [
            JwtAuthentication::class,
            GetServices::class
        ],
        'services.get'
    );
    $app->post(
        '/services',
        [
            JwtAuthentication::class,
            CreateService::class
        ],

        'services.create'
    );
    $app->get(
        '/services/{serviceId}',
        [
            JwtAuthentication::class,
            ValidateServiceId::class,
            GetService::class
        ],
        'service.get'
    );
    $app->patch(
        '/services/{serviceId}',
        [
            JwtAuthentication::class,
            ValidateServiceId::class,
            UpdateService::class
        ],
        'service.update'
    );
    $app->delete(
        '/services/{serviceId}',
        [
            JwtAuthentication::class,
            ValidateServiceId::class,
            DeleteService::class
        ],
        'service.delete'
    );



    //     // TEMPORARY ERROR HANDLING TEST
};
