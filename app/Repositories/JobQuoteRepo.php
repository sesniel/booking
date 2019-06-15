<?php

namespace App\Repositories;

use Bouncer;
use App\JobQuote;
use App\Repositories\FileRepo;
use Illuminate\Support\Carbon;
use App\Repositories\MediaRepo;
use Illuminate\Support\Facades\Auth;

class JobQuoteRepo
{
    private $tax;
    private $amount;

    public function __construct()
    {
        $this->tax = 10;// percent
        $this->amount = 0;
    }

    public function create(array $data)
    {
        $this->computeAmount($data['specs']['costs'] ?? []);
        $tax = round($this->computeTotalTax(), 2);
        $data['total'] = round($this->amount + $tax, 2);
        $data['specs'] = $this->compostSpecs($data['specs']);
        $data['user_id'] = Auth::user()->id;
        $data['status'] = $data['status'] === 'pending response' ? 1 : 0;

        if (!isset($data['duration']) || ! $data['duration']) {
            $data['duration'] = Carbon::now()->addDays(7);
        }

        return JobQuote::create($data);
    }

    public function update(array $data, JobQuote $JobQuote)
    {
        $this->computeAmount($data['specs']['costs'] ?? []);
        $tax = round($this->computeTotalTax(), 2);
        $data['total'] = round($this->amount + $tax, 2);
        $data['specs'] = $this->compostSpecs($data['specs']);
        $data['status'] = $data['status'] === 'pending response' ? 1 : 0;

        if (!isset($data['duration']) || ! $data['duration']) {
            $data['duration'] = Carbon::now()->addDays(7);
        }

        return $JobQuote->update($data);
    }

    private function compostSpecs(array $specs)
    {
        if (!isset($specs['titles']) || !isset($specs['costs'])) {
            return null;
        }

        $titles = $specs['titles'];
        $costs = $specs['costs'];
        $specs = [];

        foreach ($titles as $key => $title) {
            if ($title || $costs[$key] || $notes[$key]) {
                $specs[] = [
                    'title' => $title,
                    'cost' => isset($costs[$key]) ? $costs[$key] : null,
                ];
            }
        }

        return $specs;
    }

    private function computeAmount(array $costs)
    {
        $this->amount = collect($costs)->reduce(function ($total, $cost) {
            return $total + $cost;
        });

        return $this;
    }

    private function computeTotalTax()
    {
        if ((int)request('apply_gst') === 1) {
            return ($this->tax / 100) * $this->amount;
        }

        return 0;
    }

    public function uploadTC($tcFile)
    {
        if (request('tac_option') === 'new' && $tcFile) {
            $tc = with(new FileRepo)->store(Auth::user()->id, $tcFile, 'tc');

            if ($tc) {
                return request()->request->add(['tc_file_id' => $tc->id]);
            }
        }

        if (request('tac_option') === 'existing' && $tcFile) {
            $tc = File::where('user_id', Auth::user()->id)
                ->where('meta_key', 'tc')->first(['id']);

            if ($tc) {
                return request()->request->add(['tc_file_id' => $tc->id]);
            }
        }

        return request()->request->add(['tc_file_id' => null]);
    }

    public function get($jobQuoteId)
    {
        return JobQuote::whereId($jobQuoteId)->with([
            'jobPost' => function ($q) {
                $q->addSelect([
                    'id', 'user_id', 'event_id', 'specifics', 'budget', 'event_date', 'job_category_id', 'status',
                    'number_of_guests', 'job_time_requirement_id',
                ])->with([
                    'category' => function ($q) {
                        $q->addSelect(['id', 'name']);
                    },
                    'timeRequirement' => function ($q) {
                        $q->addSelect(['job_time_requirements.id', 'name']);
                    },
                    'locations' => function ($q) {
                        $q->addSelect(['locations.id', 'name']);
                    },
                    'event' => function ($q) {
                        $q->addSelect(['id', 'name']);
                    },
                    'userProfile' => function ($q) {
                        $q->addSelect(['id', 'userA_id', 'title', 'profile_avatar']);
                    }
                ]);
            },
            'user' => function ($q) {
                $q->addSelect(['id'])->with([
                    'vendorProfile' => function ($q) {
                        $q->addSelect(['id', 'user_id', 'business_name', 'location_id', 'profile_avatar'])
                            ->with(['location' => function ($q) {
                                $q->addSelect(['id', 'name']);
                            }]);
                    }
                ]);
            },
            'milestones' => function ($q) {
                $q->addSelect(['id', 'percent', 'due_date', 'desc', 'job_quote_id']);
            },
            'tcFile' => function ($q) {
                $q->addSelect(['id', 'meta_filename', 'meta_original_filename']);
            },
            'additionalFiles' => function ($q) {
                $q->addSelect(['files.id', 'meta_filename', 'meta_original_filename']);
            }
        ])->first([
            'id', 'user_id', 'job_post_id', 'message', 'specs', 'total', 'duration',
            'tc_file_id', 'confirmed_dates', 'apply_gst', 'locked', 'status',
        ]);
    }
}
