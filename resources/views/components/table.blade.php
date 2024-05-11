<div class="table-responsive">
    <table class="{{ $class }}" {{ $attributes }}>
        @isset($header)
            <thead>
                {{ $header }}
            </thead>
        @endisset

        <body>
            @isset($body)
                {{ $body }}
            @endisset
        </body>
        @isset($tfoot)
            <tfoot>
                {{ $tfoot }}
            </tfoot>
        @endisset
    </table>
</div>
