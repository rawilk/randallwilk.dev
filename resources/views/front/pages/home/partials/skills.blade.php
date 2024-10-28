<section id="skills" class="py-20 bg-white">
    <div class="wrap">
        <h2 class="title-2xl">{{ __('My Skills') }}</h2>
    </div>

    <div class="wrap space-y-20 pb-20">
        @foreach (App\Enums\SkillType::cases() as $case)
            <x-front.section-list :heading="$case->getLabel()" id="skill-{{ $case->value }}">
                @foreach (config("randallwilk.skills.{$case->value}") ?? [] as $skill => $skillAttrs)
                    <x-front.skill-list-item skill="{{ $skill }}" :data="$skillAttrs" />
                @endforeach
            </x-front.section-list>
        @endforeach
    </div>
</section>
