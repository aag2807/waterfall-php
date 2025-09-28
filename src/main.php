<?php

namespace Waterfall;

use Waterfall\Utils\ControllerRegistry;
use Waterfall\Utils\RouterConfig;

ControllerRegistry::registerControllers();
RouterConfig::listen();
