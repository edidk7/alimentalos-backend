<?php

namespace App\Repositories;

use Cog\Contracts\Love\Reactable\Models\Reactable;
use Cog\Contracts\Love\Reacterable\Models\Reacterable;
use Cog\Laravel\Love\Reactant\Models\Reactant;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;

class LikeRepository
{
    /**
     * Generate stats.
     *
     * @param Reactant $reactant
     * @param Reactable $reacterable
     * @return array
     */
    public function generateStats($reactant, $reacterable)
    {
        return [
            'reactable' => [
                'like' => $this->reacted($reacterable, $reactant, 'Like'),
                'pray' => $this->reacted($reacterable, $reactant, 'Pray'),
                'love' => $this->reacted($reacterable, $reactant, 'Love'),
                'hate' => $this->reacted($reacterable, $reactant, 'Hate'),
                'dislike' => $this->reacted($reacterable, $reactant, 'Dislike'),
                'sad' => $this->reacted($reacterable, $reactant, 'Sad'),
            ],
            'reactant' => $reactant->getReactionCounters()
        ];
    }

    /**
     * Generate follow stats.
     *
     * @param Reactant $reactant
     * @param Reactable $reacterable
     * @return array
     */
    public function generateFollowStats(Reactant $reactant, Reactable $reacterable)
    {
        return [
            'reactable' => [
                'follow' => $this->reacted($reacterable, $reactant, 'Follow'),
            ],
            'reactant' => $reactant->getReactionCounters()
        ];
    }

    /**
     * Update likes stat.
     *
     * @param Reactant $reactant
     * @param Reactable $reacterable
     * @param string $type
     */
    public function updateLike(Reactant $reactant, Reactable $reacterable, string $type)
    {
        if (
            $reacterable->hasReactedTo(
                $reactant,
                ReactionType::fromName(
                    $type
                )
            )
        ) {
            $reacterable->unreactTo($reactant, ReactionType::fromName($type));
        } else {
            $reacterable->reactTo($reactant, ReactionType::fromName($type));
        }
    }

    /**
     * Check if reactant was reacted by reacterable (comment liked by user)
     *
     * @param Reacterable $reacterable
     * @param Reactant $reactant
     * @param $name
     * @return boolean
     */
    public function reacted(Reacterable $reacterable, Reactant $reactant, string $name)
    {
        return $reacterable->hasReactedTo($reactant, ReactionType::fromName($name));
    }
}
