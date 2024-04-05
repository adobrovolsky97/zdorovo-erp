<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notification\NotificationResource;
use App\Services\Notification\Contracts\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class NotificationController
 */
class NotificationController extends Controller
{
    /**
     * @var NotificationServiceInterface
     */
    private NotificationServiceInterface $notificationService;

    /**
     * @param NotificationServiceInterface $notificationService
     */
    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return NotificationResource::collection($this->notificationService->getAll(['is_read' => false]));
    }

    /**
     * @return JsonResponse
     */
    public function readAll(): JsonResponse
    {
        $this->notificationService->readAll();

        return response()->json(null, 204);
    }
}
