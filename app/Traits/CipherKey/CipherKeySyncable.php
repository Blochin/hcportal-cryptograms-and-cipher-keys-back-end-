<?php

namespace App\Traits\CipherKey;

use App\Models\Archive;
use App\Models\CipherKey;
use App\Models\Folder;
use App\Models\Fond;
use App\Models\Person;
use App\Models\Tag;

/*
|--------------------------------------------------------------------------
| Cipher Key Syncable
|--------------------------------------------------------------------------
|
| This trait will be used for sync relations to Cipher Key
|
*/

trait CipherKeySyncable
{
	/**
	 * Sync archive
	 *
	 * @param $model
	 * @param array $sanitized
	 * @param boolean $updateApi
	 * @return void
	 */
	public function syncArchive($model, $sanitized, $updateApi = false)
	{

		$archive_id = (isset($sanitized['archive_id'])) ? $sanitized['archive_id'] : null;

		if ($updateApi) {
			$sanitized['new_archive'] = (isset($sanitized['archive']) && $sanitized['archive']) ? $sanitized['archive'] : null;
			$sanitized['new_folder'] = (isset($sanitized['folder']) && $sanitized['folder']) ? $sanitized['folder'] : null;
			$sanitized['new_fond'] = (isset($sanitized['fond']) && $sanitized['fond']) ? $sanitized['fond'] : null;
		}

		if (
			isset($sanitized['new_archive']) &&
			$sanitized['new_archive'] &&
			$sanitized['new_archive'] !== 'null'
		) {
			$archive = Archive::firstOrCreate([
				'name' => $sanitized['new_archive'],
			]);
			$archive_id = $archive->id;
		}

		$fond_id = (isset($sanitized['fond_id'])) ? $sanitized['fond_id'] : null;

		if (
			isset($sanitized['new_fond']) &&
			$sanitized['new_fond'] &&
			$sanitized['new_fond'] !== 'null'
		) {
			$fond = Fond::firstOrCreate([
				'name' => $sanitized['new_fond'],
				'archive_id' => $archive_id,
			]);
			$fond_id = $fond->id;
		}

		$folder_id = (isset($sanitized['folder_id'])) ? $sanitized['folder_id'] : null;;

		if (
			isset($sanitized['new_folder']) &&
			$sanitized['new_folder'] &&
			$sanitized['new_folder']  !== 'null'
		) {
			$folder = Folder::firstOrCreate([
				'name' => $sanitized['new_folder'],
				'fond_id' => $fond_id,
			]);
			$folder_id = $folder->id;
		}

		if ($folder_id) {
			$model->update(['folder_id' => $folder_id]);
		}
	}

	/**
	 * Sync tags
	 *
	 * @param CipherKey $key
	 * @param array $sanitized
	 * @param string $origin
	 * @param string $type
	 * @return void
	 */
	public function syncTags(CipherKey $key, $sanitized, $origin = 'web', $type = 'cipher_key')
	{
		if (!isset($sanitized['tags']) && !$sanitized['tags']) return;

		$tags = collect($sanitized['tags'])->pluck('id');

		if ($origin == 'api') {
			$tags = collect([]);
			foreach ($sanitized['tags'] as $tag) {
				$tag = Tag::where('type', $type)->firstOrCreate(['name' => $tag], ['type' => $type]);
				$tags->push($tag->id);
			}
		}

		$key->tags()->sync($tags->toArray());
	}

	/**
	 * Sync cryptograms
	 *
	 * @param CipherKey $key
	 * @param array $sanitized
	 * @return void
	 */
	public function syncCryptograms($key, $sanitized)
	{
		$cryptograms = collect($sanitized['cryptograms'])->pluck('id')->toArray();
		$key->cryptograms()->sync($cryptograms);
	}

	/**
	 * Sync cipher key users
	 *
	 * @param CipherKey $cipherKey
	 * @param array $sanitized
	 * @return void
	 */
	public function syncCipherKeyUsers($model, $sanitized, $mode = 'create', $origin = 'web')
	{

		//Syn ingredients to model
		if (count($sanitized['users']) > 0 || $mode == 'update') {
			$model->users()->delete();


			foreach ($sanitized['users'] as $user) {

				if ($origin == 'web') {
					$newUser = $user->user;
				} else {
					$user = (object) $user;
					$newUser = Person::firstOrCreate(['name' => $user->name]);
				}


				$model->users()->create([
					'person_id' => $newUser->id,
					'is_main_user' => $user->is_main_user
				]);
			}
		}
		return true;
	}


	/**
	 * Sync cipher key images
	 *
	 * @param CipherKey $cipherKey
	 * @param array $sanitized
	 * @return void
	 */
	public function syncCipherKeyImages($cipherKey, $sanitized)
	{

		foreach ($sanitized['images'] as $key => $image) {
			$image = (array) $image;

			$img = $cipherKey->images()->create([
				'structure' => $image['structure'],
				'has_instructions' => $image['has_instructions'],
				'is_local' => true,
				'ordering' => 1
			]);


			$img
				->addMedia($sanitized['files'][$key])
				->toMediaCollection('picture');

			$img->update(['url' => $img->getFirstMediaPath('picture')]);
		}
	}
}
