<?php

use App\Console\Commands\FindMarketingSmsTargetsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(FindMarketingSmsTargetsCommand::class)->everyMinute();
