<x-guest-layout>
    <section>
        <div class="col-xl-12 col-lg-12 col-md-9 p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Tooltip Example') }}
                </h2>
            </header>
            <div class="row">
                <div class="col-md-12">
                    <!-- Example Buttons -->
                    <button class="btn btn-primary tooltip-button" data-tooltip="This is the Admin Info button.">Admin Info</button>
                    <button class="btn btn-secondary tooltip-button" data-tooltip="Click here to provide School Info.">School Info</button>
                    <button class="btn btn-success tooltip-button" data-tooltip="Set up your equipment here.">Equipment Setup</button>
                    <button class="btn btn-danger tooltip-button" data-tooltip="Finalize your configuration.">Finalize</button>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Tooltip container styling */
        .tooltip-button {
            position: relative; /* Parent for tooltip */
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        /* Tooltip text styling */
        .tooltip-button::after {
            content: attr(data-tooltip); /* Use the data-tooltip attribute */
            position: absolute;
            bottom: 150%; /* Position above the button */
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none; /* Prevent interaction */
            transition: opacity 0.3s, transform 0.3s;
            z-index: 9999;
        }

        /* Show tooltip on hover */
        .tooltip-button:hover::after {
            opacity: 1;
            transform: translateX(-50%) translateY(-5px); /* Slight upward animation */
        }
    </style>
</x-guest-layout>
