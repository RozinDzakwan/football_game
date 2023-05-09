<div class="carousel-container">
                <div class="carousel-main" id="carousel">
                    @foreach ($all_data_teams[$player_team]['shirt-team'] as $key => $value)
                    <div class="carousel-feature">
                        {{-- <a href="#"><img class="carousel-image" alt="featureCarousel-1" width="150" src="{{ asset('asset/images/shirt/EYE GAME - Pro Vercelli Home Front.png') }}"></a> --}}
                        <img class="carousel-image" src="{{ asset('asset/images/shirt/') }}/{{ $value }}" width="150px">
                    </div>
                @endforeach
                </div>
                <p>
                    <button type="button" class="btn btn-primary" id="carousel-left"><i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-primary" id="carousel-right"><i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </p>
            </div>