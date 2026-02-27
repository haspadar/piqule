<?php

return [
    'complexity' => [
        'max_cyclomatic_per_method' => << config(phpmetrics.complexity.max_cyclomatic_per_method)|default_list(["10"])|join("") >>,
        'max_weighted_methods_per_class' => << config(phpmetrics.complexity.max_weighted_methods_per_class)|default_list(["20"])|join("") >>,
    ],
    'size' => [
        'max_loc_per_class' => << config(phpmetrics.size.max_loc_per_class)|default_list(["1000"])|join("") >>,
        'max_logical_loc_per_class' => << config(phpmetrics.size.max_logical_loc_per_class)|default_list(["600"])|join("") >>,
        'max_logical_loc_per_method' => << config(phpmetrics.size.max_logical_loc_per_method)|default_list(["20"])|join("") >>,
    ],
    'halstead' => [
        'max_volume_per_method' => << config(phpmetrics.halstead.max_volume_per_method)|default_list(["1000"])|join("") >>,
        'max_difficulty_per_method' => << config(phpmetrics.halstead.max_difficulty_per_method)|default_list(["15"])|join("") >>,
        'max_effort_per_method' => << config(phpmetrics.halstead.max_effort_per_method)|default_list(["15000"])|join("") >>,
        'max_bugs_per_method' => << config(phpmetrics.halstead.max_bugs_per_method)|default_list(["0.5"])|join("") >>,
    ],
    'inheritance' => [
        'max_depth' => << config(phpmetrics.inheritance.max_depth)|default_list(["3"])|join("") >>,
    ],
    'structure' => [
        'max_methods_per_class' => << config(phpmetrics.structure.max_methods_per_class)|default_list(["10"])|join("") >>,
    ],
    'coupling' => [
        'max_afferent' => << config(phpmetrics.coupling.max_afferent)|default_list(["10"])|join("") >>,
        'max_efferent' => << config(phpmetrics.coupling.max_efferent)|default_list(["10"])|join("") >>,
    ],
];
