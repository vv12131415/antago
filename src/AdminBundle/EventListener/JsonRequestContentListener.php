<?php

namespace AdminBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * PHP does not populate $_POST with the data submitted via a JSON Request,
 * causing an empty $request->request.
 *
 * This listener fixes this.
 */
class JsonRequestContentListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $hasBeenSubmitted = in_array($request->getMethod(), ['PATCH', 'POST', 'PUT'], true);
        $isJson = (1 === preg_match('#application/json#', $request->headers->get('Content-Type')));
        if (!$hasBeenSubmitted || !$isJson) {
            return;
        }
        $data = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            $event->setResponse(new Response(
                '{"error":"Invalid or malformed JSON"}',
                400,
                ['Content-Type' => 'application/json']));
        }
        $request->request->add($data ? $data : []);
    }
}