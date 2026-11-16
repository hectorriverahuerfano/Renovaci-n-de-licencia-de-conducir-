<?php

namespace App\Exceptions;

use Exception;

class ExternalServiceTimeoutException extends Exception
{
    // Excepción de dominio para caídas o latencias excesivas en APIs de RNPN / VMT / SERTRACEN
}
