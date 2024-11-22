<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $plan_id
 * @property int $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUpgrade whereUserId($value)
 */
	class AccountUpgrade extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $url
 * @property int $trash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppHelp whereUrl($value)
 */
	class AppHelp extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereValue($value)
 */
	class AppSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $buyer_id
 * @property string $name
 * @property string $father_name
 * @property string $country_code
 * @property string $mobile
 * @property string|null $email
 * @property string $parent_id
 * @property int $is_fixed_rate 0=false,1=true
 * @property int $fixed_rate_type 0=rate,1=fat_rate
 * @property float $rate
 * @property float $fat_rate
 * @property int $trash 0=false,1=true
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $password
 * @property string|null $fcm_token
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MilkBuyRecords> $milkrecord
 * @property-read int|null $milkrecord_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers query()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereFatRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereFixedRateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereIsFixedRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyers whereUpdatedAt($value)
 */
	class Buyers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $seller_id
 * @property string $product_id
 * @property string $name
 * @property string $image
 * @property string $unit_type
 * @property float $price
 * @property int $weight
 * @property float $tax
 * @property float $discount
 * @property int $quantity
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUnitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereWeight($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $role_id
 * @property string $permission_id
 * @property int $access
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed query()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed whereAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerPermissionAllowed whereUserId($value)
 */
	class DealerPermissionAllowed extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $permission_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions query()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRolePermissions whereUpdatedAt($value)
 */
	class DealerRolePermissions extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $role_id
 * @property string $short_name
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles query()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRoles whereUpdatedAt($value)
 */
	class DealerRoles extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $farmer_id
 * @property string $name
 * @property string $father_name
 * @property string $country_code
 * @property string $mobile
 * @property string|null $email
 * @property string $password
 * @property string $parent_id
 * @property int $is_fixed_rate 0=false,1=true
 * @property int $fixed_rate_type 0=rate,1=fat_rate
 * @property float $rate
 * @property float $fat_rate
 * @property int $trash 0=false,1=true
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $fcm_token
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \App\Models\MilkBuyRecords|null $milkrecord
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereFatRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereFixedRateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereIsFixedRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereUpdatedAt($value)
 */
	class Farmer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $device_id
 * @property string $token
 * @property string|null $user_id
 * @property string $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginTokens whereUserId($value)
 */
	class LoginTokens extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $message
 * @property int $is_marked
 * @property int $message_type 1=message,2=milk buy request,
 * @property int|null $record_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereIsMarked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereMessageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessagesAlert whereUserId($value)
 */
	class MessagesAlert extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $seller_id
 * @property string $buyer_id
 * @property int $record_type 0=farmer,1=user,2=unknown
 * @property int $milk_type 0=Cow,1=Buffalo,2=Mix,3=Other
 * @property string $shift
 * @property float $quantity
 * @property float $fat
 * @property float $snf
 * @property float $clr
 * @property float $bonus
 * @property float $price
 * @property float $total_price
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property string|null $country_code
 * @property string|null $mobile
 * @property int $is_accepted
 * @property int $trash
 * @property int $is_deleted
 * @property-read \App\Models\User|null $buyer
 * @property-read \App\Models\Farmer|null $seller
 * @property-read \App\Models\MilkTransportRecords|null $transportdetails
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords query()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereClr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereFat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereIsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereMilkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereRecordType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereSnf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords withCostumer()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkBuyRecords withTransportDetails()
 */
	class MilkBuyRecords extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $chart_type
 * @property string $milk_type
 * @property float $fat
 * @property float $snf
 * @property float $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart query()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereChartType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereFat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereMilkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereSnf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkRateChart whereUserId($value)
 */
	class MilkRateChart extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $seller_id
 * @property string|null $buyer_id
 * @property int $record_type 0=buyer,1=user,2=unknown
 * @property int $milk_type 0=Cow,1=Buffalow,2=Mix,3=Other
 * @property float $quantity
 * @property string $shift
 * @property float $fat
 * @property float $snf
 * @property float $clr
 * @property float $bonus
 * @property float $price
 * @property float $total_price
 * @property string $date
 * @property int $trash
 * @property int $is_deleted
 * @property string|null $name
 * @property string|null $country_code
 * @property string|null $mobile
 * @property int $is_accepted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Buyers|null $buyer
 * @property-read \App\Models\Farmer|null $seller
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords query()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereClr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereFat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereIsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereMilkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereRecordType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereSnf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkSaleRecords withCostumer()
 */
	class MilkSaleRecords extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $record_id
 * @property string|null $transporter_id
 * @property string|null $route_id
 * @property int $is_transport
 * @property string|null $pickedup
 * @property string|null $delivered
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords query()
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereDelivered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereIsTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords wherePickedup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereTransporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MilkTransportRecords whereUpdatedAt($value)
 */
	class MilkTransportRecords extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $order_id
 * @property string $product_id
 * @property string $name
 * @property string $image
 * @property string $unit_type
 * @property float $price
 * @property int $weight
 * @property float $tax
 * @property float $discount
 * @property int $quantity
 * @property float $total
 * @property-read \App\Models\Orders|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUnitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereWeight($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $order_id
 * @property string $buyer_id
 * @property string $seller_id
 * @property string $product_id
 * @property string $payment_id
 * @property int $quantity
 * @property int $status 0=cancelled,1=new,2=acccepted,3=outfordelevery,4=delevered,5=completed,6=return,7=Rejected
 * @property int $payment_method 1=COD,2=ONLINE
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_id
 * @property-read \App\Models\OrderItem|null $order_items
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders query()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUpdatedAt($value)
 */
	class Orders extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $plan_id
 * @property string $user_id
 * @property string|null $payment_id
 * @property string|null $tnx_id
 * @property string|null $payment_method
 * @property int $payment_status 1=initiated,2=complete,3=cancelled
 * @property float|null $amount
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int $status 1=active,0=inactive,2=expired
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pakeage|null $plan
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy query()
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereTnxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackagePurchaseHistroy whereUserId($value)
 */
	class PackagePurchaseHistroy extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $plan_id
 * @property string $name
 * @property string $category
 * @property string $user_count
 * @property float $price
 * @property int $duration
 * @property string $duration_type
 * @property string|null $description
 * @property string $status
 * @property string $farmer_count
 * @property string|null $module_access
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereDurationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereFarmerCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereModuleAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pakeage whereUserCount($value)
 */
	class Pakeage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $country_code
 * @property string $mobile
 * @property int $account_type 1=dairy,0=child dairy,2=farmer,3=buyer
 * @property string $token
 * @property string|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereToken($value)
 */
	class PasswordReset extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $printer_type
 * @property string $name
 * @property string|null $port
 * @property int $is_default
 * @property int $trash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePrinterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereUserId($value)
 */
	class Printer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $group
 * @property string $brand
 * @property string $name
 * @property string $desciption
 * @property string $image
 * @property string $unit_type
 * @property float $price
 * @property int $is_tax
 * @property float $tax
 * @property int $is_weight
 * @property float $weight
 * @property int $stock
 * @property int $trash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductsUnitTypes|null $unitType
 * @method static \Illuminate\Database\Eloquent\Builder|Products newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Products newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Products query()
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereDesciption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereIsTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereIsWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereUnitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereWeight($value)
 */
	class Products extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $group
 * @property string $brand
 * @property int $trash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsBrands whereUserId($value)
 */
	class ProductsBrands extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $group
 * @property int $trash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsGroup whereUserId($value)
 */
	class ProductsGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $unit
 * @property string $name
 * @property int $trash
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsUnitTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsUnitTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsUnitTypes query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsUnitTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsUnitTypes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsUnitTypes whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductsUnitTypes whereUnit($value)
 */
	class ProductsUnitTypes extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $route_id
 * @property string|null $parent_id
 * @property string $route_name
 * @property int $is_assigned
 * @property string|null $transporter_id
 * @property int $is_driver
 * @property string|null $driver_id
 * @property int $trash
 * @property int $deleted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoutesDairyList> $dairies
 * @property-read int|null $dairies_count
 * @property-read \App\Models\TransportDrivers|null $driver
 * @property-read \App\Models\Transporters|null $transporter
 * @method static \Illuminate\Database\Eloquent\Builder|Routes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Routes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Routes query()
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereIsAssigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereIsDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereTransporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereTrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Routes whereUpdatedAt($value)
 */
	class Routes extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $dairy_id
 * @property string $route_id
 * @property string $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Routes|null $routes
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList whereDairyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoutesDairyList whereUpdatedAt($value)
 */
	class RoutesDairyList extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string|null $user_id
 * @property string|null $user_type
 * @property string $client_id
 * @property string|null $name
 * @property array|null $scopes
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property-read \Laravel\Passport\Client|null $client
 * @property-read \Laravel\Passport\RefreshToken|null $refreshToken
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tokens whereUserType($value)
 */
	class Tokens extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $transporter_id
 * @property string $driver_id
 * @property string $name
 * @property string $father_name
 * @property string $country_code
 * @property string $mobile
 * @property string|null $email
 * @property string $password
 * @property int $is_verified 0=false,1=true
 * @property int $is_blocked 0=false,1=true
 * @property int $deleted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $fcm_token
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\TransportVehicle|null $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereIsBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereTransporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportDrivers whereUpdatedAt($value)
 */
	class TransportDrivers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $transporter_id
 * @property string|null $driver_id
 * @property string $vehicle_number
 * @property string $unit
 * @property int $capacity
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TransportDrivers|null $driver
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereTransporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportVehicle whereVehicleNumber($value)
 */
	class TransportVehicle extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $transporter_id
 * @property string $transporter_name
 * @property string $name
 * @property string $father_name
 * @property string $country_code
 * @property string $mobile
 * @property string|null $email
 * @property string $password
 * @property int $is_verified 0=false,1=true
 * @property int $is_blocked 0=false,1=true
 * @property int $deleted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $parent_id
 * @property string|null $fcm_token
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereIsBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereTransporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereTransporterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transporters whereUpdatedAt($value)
 */
	class Transporters extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string|null $parent_id
 * @property string $name
 * @property string $country_code
 * @property string $mobile
 * @property string|null $email
 * @property int $is_email_verified 0=false,1=true
 * @property int $is_verified 0=false,1=true
 * @property int $is_blocked 0=false,1=true
 * @property int $role 0=user,1=admin, 2 = child user
 * @property string|null $role_id
 * @property int $user_type 0=single user,1=multiple users
 * @property string|null $plan_id
 * @property string|null $plan_created
 * @property string|null $plan_expired
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $father_name
 * @property string|null $fcm_token
 * @property string|null $route_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\UserProfile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePlanCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePlanExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAds whereUserId($value)
 */
	class UserAds extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $country_code
 * @property string $user_id
 * @property int $account_type 1=dairy,0=child dairy,2=farmer,3=buyer,4=trasnporter
 * @property string $otp
 * @property string $expire_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtp whereUserId($value)
 */
	class UserOtp extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string|null $dairy_name
 * @property string $image
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereDairyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUserId($value)
 */
	class UserProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $lang
 * @property string $print_font_size
 * @property string $wight
 * @property string $print_size
 * @property int $print_recipt
 * @property int $print_recipt_all
 * @property int $whatsapp_message
 * @property int $auto_fats
 * @property int $rate_par_kg
 * @property int $fat_rate
 * @property int $snf
 * @property int $bonus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereAutoFats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereFatRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings wherePrintFontSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings wherePrintRecipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings wherePrintReciptAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings wherePrintSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereRateParKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereSnf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereWhatsappMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereWight($value)
 */
	class UserSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $mobile
 * @property string $plan_id
 * @property string $massage_plan
 * @property string $massage_plan_created
 * @property string $massage_plan_expire_date
 * @property string $massage_plan_limit
 * @property string $user_count
 * @property string $category_plan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereCategoryPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereMassagePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereMassagePlanCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereMassagePlanExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereMassagePlanLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userplanpackage whereUserCount($value)
 */
	class Userplanpackage extends \Eloquent {}
}

