<?php

declare(strict_types=1);

namespace CalendarBundle\Controller;

use CalendarBundle\Event\SetDataEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class CalendarController extends AbstractController
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    #[Route('/calendar', name: 'calendar')]
    public function load(Request $request,
    #[MapQueryParameter] array $filters=[],
    #[MapQueryParameter] ?string $start=null,
    #[MapQueryParameter] ?string $end=null,
    ): Response
    {
        try {
            if (\is_string($request->get('start'))) {
                $start = new \DateTime($request->get('start'));
            } else {
                throw new \Exception('Query parameter "start" should be a string');
            }

            if (\is_string($request->get('end'))) {
                $end = new \DateTime($request->get('end'));
            } else {
                throw new \Exception('Query parameter "end" should be a string');
            }

            $filters = $request->get('filters', '{}');
            $filters = match (true) {
                \is_array($filters) => $filters,
                \is_string($filters) => json_decode($filters, true),
                default => false,
            };

        } catch (\Exception $e) {
            if (!\is_array($filters)) {
                throw new BadRequestHttpException($e->getMessage(), $e);
            }
        }

        $setDataEvent = $this->eventDispatcher->dispatch(new SetDataEvent($start, $end, $filters));
        return $this->json($setDataEvent->getEvents());
        return new JsonResponse($setDataEvent->getEvents());
    }
}
