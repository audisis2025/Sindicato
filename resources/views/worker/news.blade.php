{{-- 
* Nombre de la vista           : news.blade.php
* Descripción de la vista      : Vista responsive para la consulta de convocatorias, comunicados y eventos
*                                publicados por el sindicato. Permite la búsqueda y filtrado por tipo de
*                                publicación y palabra clave, así como la descarga de archivos adjuntos,
*                                conforme a los lineamientos visuales y estructurales del portal Sindisoft.
* Fecha de creación            : 12/01/2026
* Elaboró                      : Iker Piza
* Fecha de liberación          : 12/01/2026
* Autorizó                     : Líder Técnico
* Versión                      : 1.0
* Fecha de mantenimiento       : N/A
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : N/A
* Descripción del mantenimiento: 
* Responsable                  : Iker Piza
* Revisor                      : QA Sindisoft
--}}

<x-layouts.app :title="__('Convocatorias y Anuncios')">

	<div class="w-full min-h-[80vh] bg-white text-black px-4 sm:px-6 py-6">

		<div class="w-full max-w-4xl mx-auto">

			<div class="flex items-start justify-between gap-4 mb-6">

				<div class="text-left">
					<h1 class="text-3xl font-bold text-[#DE6601]">
						Convocatorias y Anuncios
					</h1>

					<p class="text-[#241178]">
						Consulta los comunicados, eventos y avisos oficiales publicados por el sindicato.
					</p>
				</div>

				<a href="{{ route('worker.index') }}"
					class="inline-flex items-center gap-2 rounded-lg bg-[#241178]/10 px-4 py-2 font-semibold text-[#241178] hover:bg-[#241178]/20 transition">
					<x-heroicon-o-arrow-long-left class="w-5 h-5" />
					Regresar
				</a>

			</div>

			<form method="GET" action="{{ route('worker.news.index') }}"
				class="w-full bg-white border border-zinc-200 rounded-xl shadow-sm p-5 mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4">

				<div>
					<label class="block text-sm font-semibold text-[#241178] mb-1">
						Tipo de publicación
					</label>

					<select name="type"
						class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-[#DE6601] outline-none">
						<option value="">Todos</option>
						<option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>
							Convocatorias
						</option>
						<option value="communication" {{ request('type') == 'communication' ? 'selected' : '' }}>
							Comunicados
						</option>
						<option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>
							Eventos
						</option>
					</select>
				</div>

				<div>
					<label class="block text-sm font-semibold text-[#241178] mb-1">
						Buscar por palabra clave
					</label>

					<input type="text" name="keyword" value="{{ request('keyword') }}"
						placeholder="Becas, reunión, aviso..."
						class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-[#DE6601] outline-none">
				</div>

				<div class="flex gap-3 items-end">
					<button type="submit"
						class="w-full inline-flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition">
						<x-heroicon-o-magnifying-glass class="w-5 h-5" />
						Buscar
					</button>

					<a href="{{ route('worker.news.index') }}"
						class="w-full inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-center">
						<x-heroicon-o-x-circle class="w-5 h-5" />
						Cancelar
					</a>
				</div>

			</form>

			<div class="w-full grid gap-6">

				@forelse ($news_list as $news_item)

					<div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition">

						<h2 class="text-2xl font-bold text-[#241178] mb-1">
							{{ $news_item->title }}
						</h2>

						<p class="text-gray-600 text-sm mb-3 flex items-center gap-2">

							<span>
								Publicado el
								{{ $news_item->publication_date ? \Carbon\Carbon::parse($news_item->publication_date)->format('d/m/Y') : $news_item->created_at->format('d/m/Y') }}
							</span>

							<span
								class="px-2 py-0.5 rounded-md text-white text-xs font-semibold
								@if ($news_item->type === 'announcement') bg-[#DE6601]
								@elseif ($news_item->type === 'communication') bg-blue-600
								@elseif ($news_item->type === 'event') bg-green-600
								@endif">

								@if ($news_item->type === 'announcement')
									Convocatoria
								@elseif ($news_item->type === 'communication')
									Comunicado
								@elseif ($news_item->type === 'event')
									Evento
								@endif

							</span>

						</p>

						<p class="text-black text-base leading-relaxed mb-4">
							{{ $news_item->content }}
						</p>

						@if ($news_item->file_path)
							<a href="{{ asset('storage/' . $news_item->file_path) }}" target="_blank"
								class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-600 font-semibold text-sm">
								<x-heroicon-o-arrow-down-tray class="w-5 h-5" />
								Descargar
							</a>
						@endif

					</div>

				@empty

					<p class="text-center text-gray-500 text-sm">
						No hay publicaciones disponibles en este momento.
					</p>

				@endforelse

			</div>

		</div>

	</div>

</x-layouts.app>
