<?php

return [
    'complexity' => [
        'max_cyclomatic_per_method' => << config(phpinsights.complexity.max_cyclomatic_per_method)|default(["10"])|join("") >>,
        'max_weighted_methods_per_class' => << config(phpinsights.complexity.max_weighted_methods_per_class)|default(["20"])|join("") >>,
    ],
    'size' => [
        'max_loc_per_class' => << config(phpinsights.size.max_loc_per_class)|default(["1000"])|join("") >>,
        'max_logical_loc_per_class' => << config(phpinsights.size.max_logical_loc_per_class)|default(["600"])|join("") >>,
        'max_logical_loc_per_method' => << config(phpinsights.size.max_logical_loc_per_method)|default(["20"])|join("") >>,
    ],
    'halstead' => [
        'max_volume_per_method' => << config(phpinsights.halstead.max_volume_per_method)|default(["1000"])|join("") >>,
        'max_difficulty_per_method' => << config(phpinsights.halstead.max_difficulty_per_method)|default(["15"])|join("") >>,
        'max_effort_per_method' => << config(phpinsights.halstead.max_effort_per_method)|default(["15000"])|join("") >>,
        'max_bugs_per_method' => << config(phpinsights.halstead.max_bugs_per_method)|default(["0.5"])|join("") >>,
    ],
    'inheritance' => [
        'max_depth' => << config(phpinsights.inheritance.max_depth)|default(["3"])|join("") >>,
    ],
    'structure' => [
        'max_methods_per_class' => << config(phpinsights.structure.max_methods_per_class)|default(["10"])|join("") >>,
    ],
    'coupling' => [
        'max_afferent' => << config(phpinsights.coupling.max_afferent)|default(["10"])|join("") >>,
        'max_efferent' => << config(phpinsights.coupling.max_efferent)|default(["10"])|join("") >>,
    ],
];
